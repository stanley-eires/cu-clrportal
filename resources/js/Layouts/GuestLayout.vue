<script setup>

import { Link, router, usePage } from '@inertiajs/vue3';
import LoginButton from "@/Components/LoginButton.vue";
import { toast } from "vue3-toastify";
import { computed, watch } from 'vue';

let message = computed( () => usePage().props.flash.message )
watch( message, ( value ) => {
    if ( value ) {
        toast[ value.status ]( value.content );
        router.reload( { only: undefined } )
    }
}, { deep: true } )

</script>

<template>
    <div class="main-container">
        <nav class="navbar navbar-expand-sm navbar-light bg-white">
            <div class=" container d-flex align-items-center justify-content-between">
                <Link class="navbar-brand" :href="route('public.courses')">
                <img style="width:100%;height:50px;object-fit: contain;"
                    src="/clr.png" />
                </Link>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end d-print-none" tabindex="-1" id="offcanvasNavbar">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav align-items-center justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item ps-md-4">
                                <login-button></login-button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div>
            <slot />
        </div>
    </div>
</template>
<style scoped>
.navbar {
    position: sticky;
    top: 0;
    z-index: 2;
}
</style>