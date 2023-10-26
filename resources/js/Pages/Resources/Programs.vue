<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { groupBy } from 'lodash';

let props = defineProps( { programs: Object, departments: Object, title: String, keyword: String } )

let dept_colors = computed( () => {
    let dept_color = [];
    new Set( props.programs.map( e => e.department_code ) ).forEach( element => {
        dept_color[ element ] = `#${ Math.floor( Math.random() * 16777215 ).toString( 16 ) }`
    } );
    return dept_color;
} )


let dept_by_faculty = computed( () => {
    return groupBy( props.departments, ( e ) => e.college )
} )
let id = ref( [] )

let program = ref( null );

let form = useForm( {
    id: null,
    program_name: "",
    department_code: "",
    degree: "Undergraduate",
} )

let selectAll = ( ev ) => {
    id.value = ev.target.checked ? props.programs.map( e => e.id ) : [];
}
let setProgram = ( ev ) => {
    program.value = ev;
    form.program_name = ev.program_name;
    form.department_code = ev.department_code;
    form.degree = ev.degree;
    form.id = ev.id;
}
let unsetProgram = () => {
    program.value = null;
    form.reset();
}
let handleSubmit = () => {
    form.post( route( 'admin.programs.save' ), {
        preserveState: false, only: [ 'programs' ]
    } )
}
let searchform = useForm( { q: props.keyword } )
let handleSearch = () => {
    searchform.get( route( 'admin.programs' ) )
}
</script>

<template>
    <div class="row g-3">
        <div class="col-md-12 col-lg-4">
            <div class="sticky-sidebar ">
                <form @submit.prevent="handleSubmit">
                    <h6 class=" text-uppercase">{{ program ? 'Edit Program' : 'Create Program' }}
                    </h6>
                    <div class="form-floating mb-3">
                        <input class="form-control form-control-lg" v-model="form.program_name" required>
                        <label>Programe Title</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" v-model="form.department_code">
                            <optgroup :label="faculty" v-for="faculty in Object.keys(dept_by_faculty).sort()"
                                :key="faculty">
                                <option :value="i.department_code" v-for="i in dept_by_faculty[faculty]" :key="i.id">
                                    [{{ i.department_code }}] {{ i.department_name }}</option>
                            </optgroup>
                        </select>
                        <label>Department</label>
                    </div>
                    <div class="form-floating mb-5">
                        <select class="form-select" v-model="form.degree">
                            <option v-for="i in ['Undergraduate', 'Postgraduate']" :key="i">{{ i }}</option>
                        </select>
                        <label>Degree Type</label>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-primary">{{ program ? 'Update' : 'Save' }} Changes</button>
                        <button @click.prevent="unsetProgram" v-if="program" class="btn btn-xs p-0 "><i
                                class="fa fa-times-circle" aria-hidden="true"></i> Clear</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card card-body" style="min-height:50vh">
                <div class="row align-items-center mb-3">
                    <div class="col-lg-8 d-flex">
                        <div v-if="id.length > 0" class="d-flex">

                            <Link method="put" :preserve-state="false" :href="route('admin.program.bulk-actions')"
                                :data="{ program_status: 'Published', id }" class="btn  me-1 btn-sm btn-primary "><i
                                class="fa fa-check me-1"></i>
                            Publish</Link>
                            <Link method="put" :preserve-state="false" :href="route('admin.program.bulk-actions')"
                                :data="{ program_status: 'Unpublished', id }" class="btn  me-1 btn-sm text-primary "><i
                                class="fa fa-times me-1"></i>
                            Unpublish</Link>
                            <Link :only="['programs']" method="put" :preserve-state="false"
                                :href="route('admin.program.bulk-actions', ['delete'])" :data="{ id }"
                                class="btn btn-sm me-1 text-danger"><i class="fa fa-trash me-1"></i>
                            Trash</Link>
                        </div>
                    </div>
                    <form class="col-12 col-lg-4 my-3" @submit.prevent="handleSearch">
                        <div class="input-group border border-secondary">
                            <input v-model="searchform.q" class="form-control border-0" type="search" autofocus
                                placeholder="Search....">
                            <div class="input-group-append">
                                <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm ">
                        <thead class="text-nowrap small">
                            <tr>
                                <td style="width:10px">
                                    <div class="form-check pt-0">
                                        <input @change="selectAll($event)" class="form-check-input" type="checkbox">
                                    </div>
                                </td>
                                <th>Program</th>
                                <th width="100px"></th>
                                <th>Courses</th>
                                <th>Department</th>
                                <th>Degree</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="programs.length">
                                <tr v-for="program in programs" :key="program.id">
                                    <td>
                                        <div class="form-check pt-0">
                                            <input class="form-check-input" type="checkbox" :value="program.id"
                                                v-model="id">
                                        </div>
                                    </td>
                                    <td><a href="" @click.prevent="setProgram(program)">{{ program.program_name }}</a></td>
                                    <td class="small text-nowrap">
                                        <Link :only="['programs']" preserve-scroll
                                            v-if="program.program_status == 'Published'"
                                            class="btn p-0 px-1 btn-sm alert-success"
                                            :href="route('admin.program.bulk-actions')"
                                            :data="{ program_status: 'Unpublished', id: [program.id] }" method="put"><i
                                            class="fa fa-check-circle me-1 text-success"></i>Published
                                        </Link>

                                        <Link :only="['programs']" preserve-scroll v-else
                                            class="btn p-0 px-1 btn-sm alert-danger"
                                            :href="route('admin.program.bulk-actions')"
                                            :data="{ program_status: 'Published', id: [program.id] }" method="put"><i
                                            class="fa fa-times-circle me-1  text-danger"></i>Unpublished</Link>
                                    </td>
                                    <td class="text-center">{{ program.courses_count }}</td>
                                    <td class="small"><span class="badge text-dark rounded-0" style="width:50px"
                                            :style="{ background: `${dept_colors[program.department_code]}` }">{{ program.department_code }}</span>
                                    </td>
                                    <td><span class="badge alert-primary rounded-0">{{ program.degree }}</span></td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td class="fs-6" colspan="6"><em>You do not have any program created on the platform.
                                    </em></td>
                            </tr>
                        </tbody>
                    </table>
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
    top: 150px;
}
</style>