<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { kebabCase } from 'lodash'
import LoginButton from "@/Components/LoginButton.vue";
import { onMounted } from 'vue';
import { toast } from "vue3-toastify";

defineProps( { title: String } )
let togglemenu = () => {
    document.querySelector( 'body' ).classList.toggle( 'menu-hidden' )
}
let roles = usePage().props.auth.user.roles;
let menus = [
    { title: 'Quick Overview', show: true, url: route( 'admin.overview' ), icon: 'fas fa-chart-simple', active: [ 'Overview', ].includes( usePage().component ) },
    { title: 'Users', show: roles.includes( 'admin' ), url: route( 'admin.users' ), icon: 'fas fa-users', active: [ 'Users', ].includes( usePage().component ) },
    {
        title: 'Resources Management', show: roles.includes( 'author' ), submenu: [
            { title: 'Programs', url: route( 'admin.programs' ), active: [ 'Resources/Programs' ].includes( usePage().component ) },
            { title: 'Courses', url: route( 'admin.courses' ), active: [ 'Resources/Courses', 'Resources/CourseBuilder' ].includes( usePage().component ) },
        ]
    }
]
onMounted( () => {
    setTimeout( () => {
        let flash = usePage().props.flash.message;
        if ( flash ) {
            toast[ flash.status ]( flash.content );
        }
        flash = null;
    } );
} )
</script>

<template>
    <Head :title="title" />
    <nav class="navbar fixed-top">
        <div class="container">
            <div class="d-flex align-items-center navbar-left">
                <a href="#" @click.prevent="togglemenu" class="menu-button d-md-none">
                    <i class="fas fa-bars fs-5"></i>
                </a>
                <Link class="" :href="route('public.courses')">
                <img style="width:100%;height:50px;object-fit: contain;"
                    src="http://clr.covenantuniversity.edu.ng/images/demo/CENTRE_FOR_LEARNING_RESOURCES.png" />
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
                    <li v-for="menu in menus" :key="menu" :class="{ active: menu.active }">
                        <template v-if="menu.show">
                            <template v-if="menu.submenu">
                                <a href="#" data-bs-toggle="collapse" :data-bs-target="`#_${kebabCase(menu.title)}`"
                                    aria-expanded="true" aria-controls="collapseMenuTypes" class="rotate-arrow-icon">
                                    <i class="fa fa-chevron-down"></i> <span class="d-inline-block">{{ menu.title }}</span>
                                </a>
                                <div :id="`_${kebabCase(menu.title)}`" class="collapse show" data-bs-parent="#menuTypes">
                                    <ul class="list-unstyled inner-level-menu">
                                        <li v-for="sub in menu.submenu" :key="sub" :class="{ active: sub.active }">
                                            <Link :href="sub.url"> <span class="d-inline-block">{{ sub.title }}</span>
                                            </Link>
                                        </li>
                                    </ul>
                                </div>
                            </template>
                            <Link v-else :href="menu.url">
                            <i :class="menu.icon"></i> <span class="d-inline-block">{{ menu.title }}</span>
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