<script setup>
import ProfilePicture from '@/Components/ProfilePicture.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { decodeCredential, googleLogout } from 'vue3-google-login';

const handleLogin = ( response ) => {
    const userData = decodeCredential( response.credential )
    useForm( {
        email: userData.email,
    } ).post( route( 'login' ) )
}
let roles = usePage().props.auth.user?.roles;
let menu = [
    { title: 'Overview', url: route( 'admin.overview' ), roles: [ 'admin', 'editor' ] },
    { title: 'Users', url: route( 'admin.users' ), roles: [ 'admin' ] },
    { title: 'Programs', url: route( 'admin.programs' ), roles: [ 'author' ] },
    { title: 'Courses', url: route( 'admin.courses' ), roles: [ 'author' ] }
]
</script>

<template>
    <GoogleLogin v-if="!$page.props.auth.user" :callback="handleLogin" />
    <template v-else>
        <div class="dropdown">
            <button class="btn p-0 d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                <span class="mb-0 me-2 fw-bold">{{ $page.props.auth.user.name }}</span>
                <ProfilePicture size="40">
                </ProfilePicture>
            </button>

            <div class="dropdown-menu dropdown-menu-right mt-3">
                <template v-for="i in menu" :key="i">
                    <Link class="dropdown-item" :href="i.url">{{ i.title }}</Link>
                </template>
                <div class="dropdown-divider my-0"></div>
                <Link @click="googleLogout" :href="route('logout')" class="dropdown-item" method="POST">Sign out</Link>
            </div>
        </div>

    </template>
</template>
<style></style>