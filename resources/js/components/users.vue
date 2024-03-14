<script setup>

import axios from 'axios';
import { ref, onMounted, reactive} from 'vue';
const users = ref([]);
const errors = ref({})
const success = ref('')

const userForm = reactive ({
    name:'',
    email:'',
    password:'',

});

const getUser = () => {
    axios.get('/api/users')
        .then((response) => {
            users.value = response.data
        })
};

const createUser = () => {
    axios.post('/api/users', userForm)
        .then((response) => {
            users.value.unshift(response.data);
            userForm.name = '';
            userForm.email = '';
            userForm.password = '';
            $("#addUser").modal('hide');
        }).catch(error => {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        })
              
};


onMounted(() => {
    getUser();
});
</script>
<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Users</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link to="/admin/dashboard">Home</router-link></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        <div class="content">
            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <button class="btn btn-primary btn-round" data-toggle="modal" data-target="#addUser">Add New User</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="user in users" :key="user.id">
                                                    <td>{{ user.id }}</td>
                                                    <td>{{ user.name }}</td>
                                                    <td>{{ user.email }}</td>
                                                    <td>{{ user.created_at }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                 
                        <div class="modal-header">
                            Add User
                        </div>
                        <div class="modal-body">
                            <form autocomplete="off">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input v-model="userForm.name" type="text" class="form-control " id="name" aria-describedby="emailHelp" placeholder="Enter Name">                                    
                                    <span class="text-red" v-if="errors?.name">{{ errors.name[0] }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input v-model="userForm.email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter Email">                                    
                                    <span class="text-red" v-if="errors?.name">{{ errors.email[0] }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input v-model="userForm.password"type="password" class="form-control" id="password" aria-describedby="emailHelp" placeholder="Enter Password">                                    
                                    <span class="text-red" v-if="errors?.password">{{ errors.password[0] }}</span>
                                </div>
                            </form> 
                                <div class="modal-footer">                                
                                    <button @click="createUser" class="btn btn-primary">Save</button>      
                                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>                                  
                                </div>

                      
                        </div>
                  
                </div>
            </div>
        </div>
    </div>
</template>
