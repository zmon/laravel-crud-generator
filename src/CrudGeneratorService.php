<?php

namespace CrudGenerator;


use Illuminate\Console\Command;
use DB;
use Artisan;

class CrudGeneratorService 
{
    
    public $modelName = '';
    public $tableName = '';
    public $prefix = '';
    public $force = false;
    public $layout = '';
    public $existingModel = '';
    public $controllerName = '';
    public $viewFolderName = '';
    public $output = null;
    public $appNamespace = 'App';

 
    public function __construct()
    {

    }

  
    public function Generate() 
    {
        $modelname = ucfirst(str_singular($this->modelName));
        $this->viewFolderName = strtolower($this->controllerName);

        $this->output->info('');
        $this->output->info('Creating catalogue for table: '.($this->tableName ?: strtolower(str_plural($this->modelName))));
        $this->output->info('Model Name: '.$modelname);

        $model_singular = strtolower($modelname);

        $options = [
            'model_uc' => $modelname,
            'model_uc_plural' => str_plural($modelname),
            'model_singular' => $model_singular,
            'model_plural' => strtolower(str_plural($modelname)),
            'tablename' => $this->tableName ?: strtolower(str_plural($this->modelName)),
            'prefix' => $this->prefix,
            'custom_master' => $this->layout ?: 'crudgenerator::layouts.master',
            'controller_name' => $this->controllerName,
            'view_folder' => $this->viewFolderName,
            'route_path' => $model_singular, // $this->viewFolderName,
            'appns' => $this->appNamespace,
        ];

        if(!$this->force) { 
            //if(file_exists(app_path().'/'.$modelname.'.php')) { $this->output->info('Model already exists, use --force to overwrite'); return; }
            if(file_exists(app_path().'/Http/Controllers/'.$this->controllerName.'Controller.php')) { $this->output->info('Controller already exists, use --force to overwrite'); return; }
            if(file_exists(base_path().'/resources/views/'.$this->viewFolderName.'/add.blade.php')) { $this->output->info('Add view already exists, use --force to overwrite'); return; }
            if(file_exists(base_path().'/resources/views/'.$this->viewFolderName.'/show.blade.php')) { $this->output->info('Show view already exists, use --force to overwrite'); return; }
            if(file_exists(base_path().'/resources/views/'.$this->viewFolderName.'/index.blade.php')) { $this->output->info('Index view already exists, use --force to overwrite');  return; }
        }


        $columns = $this->createModel($modelname, $this->prefix, $this->tableName);
        
        $options['columns'] = $columns;
        $options['first_column_nonid'] = count($columns) > 1 ? $columns[1]['name'] : '';
        $options['num_columns'] = count($columns);
        
        //###############################################################################
        if(!is_dir(base_path().'/resources/views/'.$this->viewFolderName)) { 
            $this->output->info('Creating directory: '.base_path().'/resources/views/'.$this->viewFolderName);
            mkdir( base_path().'/resources/views/'.$this->viewFolderName); 
        }


        $filegenerator = new \CrudGenerator\CrudGeneratorFileCreator();
        $filegenerator->options = $options;
        $filegenerator->output = $this->output;

        $filegenerator->templateName = 'controller';
        $filegenerator->path = app_path().'/Http/Controllers/'.$this->controllerName.'Controller.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'Api';
        $filegenerator->path = app_path().'/Http/Controllers/'.$this->controllerName.'Api.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'StoreRequest';
        $filegenerator->path = app_path().'/Http/Requests/'.$modelname.'StoreRequest.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'EditRequest';
        $filegenerator->path = app_path().'/Http/Requests/'.$modelname.'EditRequest.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'model';
        $filegenerator->path = app_path().'/'.$modelname.'.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'view.create';
        $filegenerator->path = base_path().'/resources/views/'.$this->viewFolderName.'/create.blade.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'view.edit';
        $filegenerator->path = base_path().'/resources/views/'.$this->viewFolderName.'/edit.blade.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'view.show';
        $filegenerator->path = base_path().'/resources/views/'.$this->viewFolderName.'/show.blade.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'view.index';
        $filegenerator->path = base_path().'/resources/views/'.$this->viewFolderName.'/index.blade.php';
        $filegenerator->Generate();

        $filegenerator->templateName = 'Grid.vue';
        $filegenerator->path = base_path().'/resources/js/components/'.$modelname.'Grid.vue';
        $filegenerator->Generate();



        //###############################################################################


        // ### VUE JS ###

        $addvue = "Vue.component('" . $model_singular . "-grid',       require('./components/" . $modelname . "Grid.vue').default);";
        $this->appendToEndOfFile(base_path().'/resources/js/components.js', "\n".$addvue, 0, true);
        $this->output->info('Adding Vue: '.$addvue );

        # $model_singular


        // ### ROUTES ###
        $addroute = 'Route::resource(\'/'.$model_singular.'\', \''.$this->controllerName.'Controller\');';
        $this->appendToEndOfFile(base_path().'/routes/web.php', "\n".$addroute, 0, true);
        $this->output->info('Adding Route: '.$addroute );

        $addroute = 'Route::get(\'/api-'.$model_singular.'\', \''.$this->controllerName.'Api@index\');';
        $this->appendToEndOfFile(base_path().'/routes/web.php', "\n".$addroute, 0, true);
        $this->output->info('Adding Route: '.$addroute );





    }


    protected function getColumns($tablename) {
        $dbType = DB::getDriverName();
        switch ($dbType) {
            case "pgsql":
                $cols = DB::select("select column_name as Field, "
                                . "data_type as Type, "
                                . "is_nullable as Null "
                                . "from INFORMATION_SCHEMA.COLUMNS "
                                . "where table_name = '" . $tablename . "'");
                break;
            default:
                $cols = DB::select("show columns from " . $tablename);
                break;
        }

        $ret = [];
        foreach ($cols as $c) {
            $field = isset($c->Field) ? $c->Field : $c->field;
            $type = isset($c->Type) ? $c->Type : $c->type;
            $null = $c->Null;  // NO
            $primary_key = ($c->Key = 'PRI' ? true : false);
            $default = $c->Default;
            if ($x = preg_match( "/\((\d+)\)/", $c->Type, $out)) {
                $size = (int) $out[1];
            } else {
                $size = false;
            }

            $cadd = [];

            $cadd['name'] = $field;
            $cadd['type'] = $type = $field == 'id' ? 'id' : $this->getTypeFromDBType($type);
            $cadd['display'] = ucwords(str_replace('_', ' ', $field));


            $validation = '';

            switch ($field) {
                case 'created':
                case 'created_at':
                case 'created_by':
                case 'modified':
                case 'updated_at':
                case 'modified_by':
                case 'wid':
                break;

                case 'id':

                    $cadd['validation'] = 'numeric';

                    $ret[] = $cadd;
                    break;

                default:
                    
                    switch ( $type ) {
                        case 'text':
                            $validation = "string";
                            if ( $size ) $validation .= "|max:$size";
                            break;
                        case 'number':
                            $validation = "numeric";
                            break;
                        case 'date':
                            $validation = "date";
                            if ( $size ) $validation .= "|max:$size";
                            break;
                        default:
                            $validation = "string";
                            var_dump($size);
                            if ( $size ) $validation .= "|max:$size";
                            break;
                    }

                    $cadd['validation'] = $validation;


                    $ret[] = $cadd;
                    break;
            }

        }
print_r($cadd);


        return $ret;
    }

    protected function getTypeFromDBType($dbtype) {
        if(str_contains($dbtype, 'varchar')) { return 'text'; }
        if(str_contains($dbtype, 'char')) { return 'text'; }
        if(str_contains($dbtype, 'int') || str_contains($dbtype, 'float')) { return 'number'; }
        if(str_contains($dbtype, 'date')) { return 'date'; }
        return 'unknown';
    }



    protected function createModel($modelname, $prefix, $table_name) {

//        Artisan::call('make:model', ['name' => $modelname]);
//
//
//        if($table_name) {
//            $this->output->info('Custom table name: '.$prefix.$table_name);
//            $this->appendToEndOfFile(app_path().'/'.$modelname.'.php', "    protected \$table = '".$table_name."';\n\n}", 2);
//        }
        

        $columns = $this->getColumns($prefix.($table_name ?: strtolower(str_plural($modelname))));

//        $cc = collect($columns);
//
//        if(!$cc->contains('name', 'updated_at') || !$cc->contains('name', 'created_at')) {
//            $this->appendToEndOfFile(app_path().'/'.$modelname.'.php', "    public \$timestamps = false;\n\n}", 2, true);
//        }

        $this->output->info('Model created, columns: '.json_encode($columns));
        return $columns;
    }

    protected function deletePreviousFiles($tablename, $existing_model) {
        $todelete = [
                app_path().'/Http/Controllers/'.ucfirst($tablename).'Controller.php',
                base_path().'/resources/views/'.str_plural($tablename).'/index.blade.php',
                base_path().'/resources/views/'.str_plural($tablename).'/add.blade.php',
                base_path().'/resources/views/'.str_plural($tablename).'/show.blade.php',
            ];
        if(!$existing_model) {
            $todelete[] = app_path().'/'.ucfirst(str_singular($tablename)).'.php'; 
        }
        foreach($todelete as $path) {
            if(file_exists($path)) { 
                unlink($path);    
                $this->output->info('Deleted: '.$path);
            }   
        }
    }

    protected function appendToEndOfFile($path, $text, $remove_last_chars = 0, $dont_add_if_exist = false) {
        $content = file_get_contents($path);
        if(!str_contains($content, $text) || !$dont_add_if_exist) {
            $newcontent = substr($content, 0, strlen($content)-$remove_last_chars).$text;
            file_put_contents($path, $newcontent);    
        }
    }
}
