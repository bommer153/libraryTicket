<script setup>


import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import useToastr from './../toastr.js';

const toastr = useToastr();
const users = ref([]);
const errors = ref({});
const formValues = ref();
const deletingId = ref(null);




const userForm = reactive({
    name: '',
    email: '',
    password: '',

});

const editing = ref(false);

const handleSubmit = (values) => {
    if (editing.value) {
        editUser(values);
    } else {
        createUser();
    }
};

const getUser = () => {
    axios.get('/api/users')
        .then((response) => {
            users.value = response.data
        })
};

const addUserForm = () => {
    editing.value = false;
    userForm.name = '';
    userForm.email = '';
    userForm.password = '';
    $("#myUserForm").modal('show');
};

const editUserForm = (user) => {
    editing.value = true;
    userForm.name = user.name;
    userForm.email = user.email;
    formValues.value = user;
    console.log(user);
    $("#myUserForm").modal('show');
};

const deleteConfirm = (user) => {
    deletingId.value = user.id;
    $("#deleteConfirm").modal('show');
};

const deleteUser = () => {
    axios.delete('/api/users/'+deletingId.value)
        .then(() => {
           
            toastr.success('User Successfully Deleted');
        })
};

const createUser = () => {
    axios.post('/api/users', userForm)
        .then((response) => {
            users.value.unshift(response.data);
            userForm.name = '';
            userForm.email = '';
            userForm.password = '';
            $("#myUserForm").modal('hide');

        }).catch(error => {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        })
    toastr.success('User Successfully Added');
};

const editUser = (user) => {
    axios.put('/api/users/' + formValues.value.id, user)
        .then((response) => {

            const index = users.value.findIndex(user => user.id === response.data.id);
            users.value[index] = response.data;

            userForm.name = '';
            userForm.email = '';
            userForm.password = '';
            $('#myUserForm').modal('hide');
            errors.value = {};
            toastr.success('User Successfully Updated');

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
                                    <button class="btn btn-primary btn-round" data-toggle="modal"
                                        @click="addUserForm">Add New User</button>
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
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="user in users" :key="user.id">
                                                    <td>{{ user.id }}</td>
                                                    <td>{{ user.name }}</td>
                                                    <td>{{ user.email }}</td>
                                                    <td>{{ user.created_at }}</td>
                                                    <td>
                                                        <button @click="editUserForm(user)"
                                                            class="btn btn-primary btn-sm"><i
                                                                class="fas fa-edit"></i></button>
                                                        <button @click="deleteConfirm(user)"
                                                            class="btn btn-danger btn-sm"><i
                                                                class="fas fa-times"></i></button>
                                                    </td>
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


        <div class="modal fade" id="myUserForm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add User</span>
                    </div>
                    <div class="modal-body">
                        <form autocomplete="off" :initialValues="formValues">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="userForm.name" type="text" class="form-control"
                                    :class="{ 'is-invalid': errors?.name }" id="name" placeholder="Enter Name">
                                <span class="text-red" v-if="errors?.name">{{ errors.name[0] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="userForm.email" type="email" class="form-control"
                                    :class="{ 'is-invalid': errors?.email }" id="email" placeholder="Enter Email">
                                <span class="text-red" v-if="errors?.email">{{ errors.email[0] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input v-model="userForm.password" type="password" class="form-control"
                                    :class="{ 'is-invalid': errors?.password }" id="password"
                                    placeholder="Enter Password">
                                <span class="text-red" v-if="errors?.password">{{ errors.password[0] }}</span>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button @click="handleSubmit(userForm)" class="btn btn-primary">Save</button>
                            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>


                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">Delete User</div>
                    <div class="modal-body">
                        <b><h4> Are your sure to Delete this User ?</h4></b>
                    </div>
                    <div class="modal-footer">
                        <button @click.prevent="deleteUser()" class="btn btn-primary">Delete</button>
                        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
