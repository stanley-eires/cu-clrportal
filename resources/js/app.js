import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
import '../css/app.css';
import vue3GoogleLogin from 'vue3-google-login';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Vue3Toasity from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const appName = window.document.getElementsByTagName( 'title' )[ 0 ]?.innerText || 'Laravel';



createInertiaApp( {
    title: ( title ) => `${ title } - ${ appName }`,
    resolve: ( name ) => {
        const page = resolvePageComponent(
            `./Pages/${ name }.vue`,
            import.meta.glob( "./Pages/**/*.vue" )
        );
        page.then( ( module ) => {
            module.default.layout = name.startsWith( 'Public' ) ? GuestLayout : AuthenticatedLayout;
        } );

        return page;
    },
    setup( { el, App, props, plugin } ) {
        return createApp( { render: () => h( App, props ) } )
            .use( plugin )
            .use( ZiggyVue, Ziggy )
            .use( vue3GoogleLogin, {
                clientId: '888479091012-0sfqr1n21hq3odmu1t277i4v349ghm8b.apps.googleusercontent.com'
            } ).use(
                Vue3Toasity, { autoClose: 10000, dangerouslyHTMLString: true, theme: 'colored' }
            )
            .mount( el );
    },
    progress: {
        color: 'rgb(146,44,136)',
    },
} );
