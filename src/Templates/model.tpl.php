<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class [[model_uc]] extends Model
{

    /**
     * fillable - attributes that can be mass-assigned
     */
    protected $fillable = [
    [[foreach:columns]]
        '[[i.name]]',
    [[endforeach]]
    ];

    /**
     * Get Grid/index data PAGINATED
     *
     * @param $per_page
     * @param $column
     * @param $direction
     * @param string $keyword
     * @return mixed
     */
    static function filteredData(
        $per_page,
        $column,
        $direction,
        $keyword = '')
    {
        return self::buildBaseGridQuery($column, $direction, $keyword)
            ->paginate($per_page);
    }


    public function add($attributes) {

        try {
            $this->fill($attributes)->save();
        } catch (\Exception $e) {
            info(__METHOD__ . ' line: ' . __LINE__ . ':  ' . $e->getMessage());
            return false;
        } catch (\Illuminate\Database\QueryException $e) {
            info( __METHOD__ . ' line: ' . __LINE__ . ':  ' . $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Create base query to be used by Grid, Download, and PDF
     *
     * NOTE: to override the select you must supply all fields, ie you cannot add to the
     *       fields being selected.
     *
     * @param $column
     * @param $direction
     * @param string $keyword
     * @return mixed
     */

    static function buildBaseGridQuery(
        $column,
        $direction,
        $keyword = '')
    {
        // Map sort direction from 1/-1 integer to asc/desc sql keyword
        switch ($direction) {
            case '1':
                $direction = 'desc';
                break;
            case '-1':
                $direction = 'asc';
                break;
        }

        $query = [[model_uc]]::select('*')
            ->orderBy($column, $direction);

        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        return $query;
    }

}
