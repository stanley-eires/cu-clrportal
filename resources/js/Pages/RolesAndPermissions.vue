<script setup>
import { Link, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
let props = defineProps( { permissions: Object, roles: Object } );

let handleSubmit = ( ev ) => {
    router.post( route( 'admin.roles.save' ), ev.target, { preserveState: false } )
}

</script>
<template>
    <form class="card" method="POST" :action="route('admin.roles.save')" @submit.prevent="handleSubmit($event)">
        <table class="table text-center table-sm">
            <tbody>
                <tr>
                    <td></td>
                    <th v-for="perm in permissions" :key="perm" class="text-uppercase small">{{ perm.name }}</th>
                </tr>
                <tr v-for="role in roles" :key="role">
                    <td class="text-uppercase small">{{ role.name }}</td>
                    <td v-for="perm in permissions" :key="perm">
                        <input class="form-check-input" :name="`roles[${role.name}][]`" type="checkbox" :value="perm.name"
                            :checked="role.permissions.map(e => e.id).includes(perm.id)">
                    </td>
                </tr>
            </tbody>
        </table>
        <button>Save Changes</button>
    </form>
</template>