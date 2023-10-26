<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { kebabCase } from 'lodash'
import LoginButton from "@/Components/LoginButton.vue";
import { computed, onMounted, watch } from 'vue';
import { toast } from "vue3-toastify";

defineProps( { title: String } )
let togglemenu = () => {
    document.querySelector( 'body' ).classList.toggle( 'menu-hidden' )
}
let can = ( permissions ) => permissions.some( r => usePage().props.auth.permissions.includes( r ) )

let menus = [
    { title: 'Quick Overview', show: can( [ 'access_site_stats' ] ), url: route( 'admin.overview' ), icon: 'fas fa-chart-simple', component: [ 'Overview', ] },
    { title: 'Users', show: can( [ 'manage_users' ] ), url: route( 'admin.users' ), icon: 'fas fa-users', component: [ 'Users', ] },
    { title: 'Roles & Permissions', show: usePage().props.auth.permissions.includes( 'manage_roles' ), url: route( 'admin.roles' ), icon: 'fas fa-lock-open', component: [ 'RolesAndPermissions' ] },
    {
        title: 'Resources Management', show: usePage().props.auth.permissions.includes( 'manage_programs' ) || usePage().props.auth.permissions.includes( 'manage_courses' ), submenu: [
            { title: 'Programs', show: usePage().props.auth.permissions.includes( 'manage_programs' ), url: route( 'admin.programs' ), component: [ 'Resources/Programs' ] },
            { title: 'Courses', show: usePage().props.auth.permissions.includes( 'manage_courses' ), url: route( 'admin.courses' ), component: [ 'Resources/Courses', 'Resources/CourseBuilder' ] }, ]
    }
]
let message = computed( () => usePage().props.flash.message )
watch( message, ( value ) => {
    if ( value ) {
        toast[ value.status ]( value.content );
        router.reload( { only: undefined } )
    }
}, { deep: true } )
</script>

<template>
    <Head :title="title" />
    <nav class="navbar fixed-top">
        <div class="container-fluid">
            <div class="d-flex align-items-center navbar-left">
                <a href="#" @click.prevent="togglemenu" class="menu-button d-md-none">
                    <i class="fas fa-bars fs-5"></i>
                </a>
                <Link class="" :href="route('public.courses')">
                <img style="width:100%;height:50px;object-fit: contain;" src="/clr.png" />
                </Link>
            </div>
            <div class="ms-auto">
                <login-button></login-button>
            </div>
        </div>
    </nav>

    <div class="menu">
        <div class="sub-menu">
            <div class="scroll">
                <ul class="list-unstyled" data-link="menu" id="menuTypes">
                    <li v-for="menu in menus" :key="menu" :class="{ active: menu.component?.includes($page.component) }">
                        <template v-if="menu.show">
                            <template v-if="menu.submenu">
                                <a href="#" data-bs-toggle="collapse" :data-bs-target="`#_${kebabCase(menu.title)}`"
                                    aria-expanded="true" aria-controls="collapseMenuTypes" class="rotate-arrow-icon">
                                    <i class="fa fa-chevron-down"></i> <span class="d-inline-block">{{ menu.title }}</span>
                                </a>
                                <div :id="`_${kebabCase(menu.title)}`" class="collapse show" data-bs-parent="#menuTypes">
                                    <ul class="list-unstyled inner-level-menu">
                                        <li v-for="sub in menu.submenu" :key="sub"
                                            :class="{ active: sub.component?.includes($page.component) }">
                                            <Link v-if="sub.show" :href="sub.url"> <span
                                                class="d-inline-block">{{ sub.title }}</span>
                                            </Link>
                                        </li>
                                    </ul>
                                </div>
                            </template>
                            <Link v-else :href="menu.url">
                            <i :class="menu.icon"></i> <span class="d-inline-block">{{ menu.title }} </span>
                            </Link>
                        </template>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <main>
        <div class="container-fluid">
            <div class="row  ">
                <div class="col-12">
                    <h2 class="fw-bold">{{ title }}</h2>
                    <hr class="my-2">
                </div>
            </div>
            <slot></slot>
        </div>
    </main>
</template>