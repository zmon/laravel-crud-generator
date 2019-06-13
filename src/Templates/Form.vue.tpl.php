<template>
    <form @submit.prevent="handleSubmit" class="form-horizontal">

        <div v-if="server_message !== false" class="alert alert-danger" role="alert">
            {{ this.server_message}}  <a v-if="try_logging_in" href="/login">Login</a>
        </div>
[[foreach:columns]]
[[if:i.type!='id']]
        <div class="row">
            <div class="col-md-9">
                <std-form-group label="[[i.display]]" :errors="form_errors.[[i.name]]">
                    <input type="text" class="form-control" name="[[i.name]]"
                           v-model="form_data.[[i.name]]"/>
                </std-form-group>
            </div>
        </div>
[[endif]]
[[endforeach]]

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <span v-if="this.form_data.id">Change</span>
                        <span v-else="this.form_data.id">Add</span>
                    </button>
                </div>
                <div class="col-md-6 text-right">
                    <a href="/[[route_path]]" class="btn btn-sm btn-default float-right">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "[[route_path]]-form",
        props: {
            record: {
                type: [Boolean, Object],
                default: false,
            },
            csrf_token: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                form_data: {
                    // _method: 'patch',
                    _token: this.csrf_token,
[[foreach:columns]]
  [[if:i.type=='id']]
                    [[i.name]]: 0,
  [[endif]]
  [[if:i.type=='text']]
                    [[i.name]]: '',
  [[endif]]
  [[if:i.type=='number]]
                    [[i.name]]: 0,
  [[endif]]
  [[if:i.type=='date']]
                    [[i.name]]: null,
  [[endif]]
  [[if:i.type=='unknown']]
                    [[i.name]]: '',
  [[endif]]
[[endforeach]]
                },
                form_errors: {
[[foreach:columns]]
                [[i.name]]: false,
[[endforeach]]
                },
                server_message: false,
                try_logging_in: false,

            }
        },
        mounted() {
            if (this.record !== false) {
                // this.form_data._method = 'patch';
                Object.keys(this.record).forEach(i => this.form_data[i] = this.record[i])
            } else {
                // this.form_data._method = 'post';
            }

        },
        methods: {
            async handleSubmit() {

                this.server_message = false;
                let url = '';
                let amethod = '';
                if (this.form_data.id) {
                    url = '/[[route_path]]/' + this.form_data.id;
                    amethod = 'put';
                } else {
                    url = '/[[route_path]]';
                    amethod = 'post';
                }
                await axios({
                    method: amethod,
                    url: url,
                    data: this.form_data
                })
                    .then((res) => {
                        if (res.status === 200) {
                            window.location = '/[[route_path]]';
                        } else {
                            this.server_message = res.status;
                        }
                    }).catch(error => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                // Clear errors out
                                Object.keys(this.form_errors).forEach(i => this.form_errors[i] = false);
                                // Set current errors
                                Object.keys(error.response.data.errors).forEach(i => this.form_errors[i] = error.response.data.errors[i]);
                            } else  if (error.response.status === 404) {  // Record not found
                                this.server_message = 'Record not found';
                                window.location = '/[[route_path]]';
                            } else  if (error.response.status === 419) {  // Unknown status
                                this.server_message = 'Unknown Status, please try to ';
                                this.try_logging_in = true;
                            } else  if (error.response.status === 500) {  // Unknown status
                                this.server_message = 'Server Error, please try to ';
                                this.try_logging_in = true;
                            } else {
                                this.server_message = error.response.data.message;
                            }
                        } else {
                            console.log(error.response);
                            this.server_message = error;
                        }
                    });
            }
        },
    }
</script>

