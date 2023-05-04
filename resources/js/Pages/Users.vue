<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import moment from 'moment';
import ProfilePicture from '@/Components/ProfilePicture.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref } from "vue";


let props = defineProps( { title: String, users: Object } );
let user = ref( null );
let show_form = ref( true );
let id = ref( [] )
let selectAll = ( ev ) => {
    id.value = ev.target.checked ? props.users.data.map( e => e.id ) : [];
}
let form = useForm( {
    id: null,
    name: "",
    email: "",
    user_group: "Faculty",
    roles: [ 'reader' ]
} )
let setUser = ( ev ) => {
    user.value = ev;
    form.name = ev.name;
    form.email = ev.email;
    form.user_group = ev.user_group;
    form.roles = ev.roles;
    form.id = ev.id;
    show_form.value = true;
}
let unsetProgram = () => {
    show_form.value = !show_form.value;
    user.value = null;
    form.reset();
}
let handleSubmit = () => {
    form.post( route( 'admin.user.save' ), { preserveState: false } )
}
let handleUsersUpload = ( ev ) => {
    if ( ev.target.files[ 0 ] ) {
        let f = useForm( {
            csv_file: ev.target.files[ 0 ]
        } )
        f.post( route( 'admin.users.upload' ) ).reset()
    }
}

</script>
<template>
    <div class="row">
        <div class="col-md-4 " v-if="show_form">
            <div class="sticky-sidebar ">
                <form action="" class="card card-body" @submit.prevent="handleSubmit">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" v-model="form.name" required>
                        <label>Fullname</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" v-model="form.email" required>
                        <label>Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-control" v-model="form.user_group" required>
                            <option value="Faculty">Faculty</option>
                            <option value="Staff">Staff</option>
                            <option value="Student">Student </option>
                            <option value="Others">Others </option>
                        </select>
                        <label>User Group</label>
                    </div>
                    <label class="text-muted small mb-1">Roles</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input v-model="form.roles" class="form-check-input" type="checkbox" id="admin"
                                    value="admin">
                                <label class="small" for="admin">Admin</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input v-model="form.roles" class="form-check-input" type="checkbox" id="author"
                                    value="author">
                                <label class="small" for="author">Author</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input v-model="form.roles" disabled class="form-check-input" type="checkbox" id="reader"
                                    value="reader">
                                <label class="small" for="reader">Reader</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary my-4">Save Changes</button>
                    <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#contentId">
                        <i class="fas fa-cloud-upload"></i> Or Bulk upload users <i class="fas fa-user-plus"></i>
                    </button>
                    <div class="collapse" id="contentId">
                        <div class="custom-file my-3">
                            <input @change="handleUsersUpload" class="custom-file-input small" type="file">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md">
            <div class="card card-body shadow" style="min-height:50vh">
                <div class="d-flex justify-content-between mb-3">
                    <div v-if="id.length > 0">
                        <Link method="put" :preserve-state="false" :href="route('admin.users.bulk-actions')"
                            :data="{ status: 1, id }" class="btn btn-sm me-1 btn-primary "><i class="fa fa-undo me-1"></i>
                        Unblock</Link>
                        <Link method="put" :preserve-state="false" :href="route('admin.users.bulk-actions')"
                            :data="{ status: 0, id }" class="btn me-1 btn-sm text-primary "><i class="fa fa-times me-1"></i>
                        Block</Link>
                        <Link method="put" :preserve-state="false" :href="route('admin.users.bulk-actions', ['delete'])"
                            :data="{ id }" class="btn me-1 btn-sm text-danger "><i class="fa fa-trash me-1"></i>
                        Trash</Link>
                    </div>
                    <template v-else>
                        <div class="d-flex">
                            <button v-if="!show_form" @click.prevent="unsetProgram()" class="btn btn-primary btn-sm"><i
                                    class="fa fa-plus"></i> Add New</button>
                            <button v-else @click.prevent="unsetProgram()" class="btn btn-sm"><i
                                    class="fa fa-close me-2"></i>Hide Form</button>
                            <div class="dropdown open ms-2">
                                <button class="btn border-primary btn-sm text-primary" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="triggerId">
                                    <Link as="button" method="POST" class="dropdown-item" :href="route('seedings.users')">
                                    <i class="fas fa-user-plus    "></i> Seed Users</Link>
                                    <a :href="route('admin.users.download')" as="button" class="dropdown-item">
                                        <i class="fas fa-download"></i> Download Users</a>
                                </div>
                            </div>
                        </div>
                    </template>
                    <form action="" class="col-lg-4">
                        <div class="input-group border border-secondary align-items-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-0 pe-0"><i
                                        class="fa fa-search"></i></span>
                            </div>
                            <input class="form-control border-0 " type="search" placeholder="Search...">
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm small">
                        <thead class="text-nowrap">
                            <tr>
                                <th style="width:2px">
                                    <div class="form-check pt-0">
                                        <input @change="selectAll($event)" class="form-check-input" type="checkbox">
                                    </div>
                                </th>
                                <th>Users</th>
                                <th></th>
                                <th>Status</th>
                                <th>Date Joined</th>
                                <th>Last Login</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for='user in users.data' :key="user.id">
                                <td>
                                    <div class="form-check pt-0">
                                        <input class="form-check-input" type="checkbox" :value="user.id" v-model="id">
                                    </div>
                                </td>
                                <td class="text-uppercase">
                                    <div class="d-flex position-relative align-items-center">
                                        <div class="flex-shrink-0">
                                            <ProfilePicture :value="user.profile_picture" size="25">
                                            </ProfilePicture>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <a href="#" @click.prevent="setUser(user)">
                                                {{ user.name }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i v-if="user.roles.includes('admin')" class="fas fa-fire-flame-curved px-1 text-danger"
                                        title="Administrator"></i>
                                    <i v-if="user.roles.includes('author')" class="fas fa-file-pen  px-1 text-primary"
                                        title="Author"></i>
                                    <i v-if="user.roles.includes('reader')" class="fas fa-book-open-reader px-1"
                                        title="Reader"></i>
                                </td>
                                <td class="text-nowrap">

                                    <Link preserve-scroll v-if="user.status" class="btn p-0 px-1 btn-sm alert-success"
                                        :href="route('admin.users.bulk-actions')" :data="{ status: 0, id: [user.id] }"
                                        method="put"><i class="fa fa-check-circle me-2 text-success"></i>Active
                                    </Link>

                                    <Link preserve-scroll v-else class="btn p-0 px-1 btn-sm alert-danger"
                                        :href="route('admin.users.bulk-actions')" :data="{ status: 1, id: [user.id] }"
                                        method="put"><i class="fa fa-times-circle me-2  text-danger"></i>Block</Link>
                                </td>

                                <td class="text-nowrap">{{ moment(user.created_at).format('L') }}</td>
                                <td> <em>{{ user.login_at ? moment(user.login_at).fromNow() : "--" }}</em></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <pagination :data="users"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.sticky-sidebar {
    height: auto;
    position: -webkit-sticky;
    position: sticky;
    top: 100px;
}
</style>