<script setup>
import { Link } from "@inertiajs/vue3";
import moment from 'moment';
import { ref } from "vue";
import Pagination from '@/Components/Pagination.vue';
let props = defineProps( {
    courses: Object, title: String
} )
let id = ref( [] )
let selectAll = ( ev ) => {
    id.value = ev.target.checked ? props.courses.data.map( e => e.id ) : [];
}
</script>

<template>
    <div class="btn-group btn-group-sm mb-3">
        <div v-if="id.length == 0" class="d-flex">
            <Link :href="route('admin.course.create')" @click.prevent="submit" class="btn btn-primary  me-1">
            <i class="fa fa-plus-circle  me-1  "></i>
            New</Link>
            <div class="dropdown">
                <button id="my-dropdown" class="btn border me-1 border-primary text-primary " data-bs-toggle="dropdown"><i
                        class="fas fa-ellipsis-h"></i></button>
                <div class="dropdown-menu small" aria-labelledby="my-dropdown">
                    <Link :href="route('seedings.departments')" method="post" class="dropdown-item ">Seed Departments
                    </Link>
                    <Link :href="route('seedings.programs')" method="post" class="dropdown-item ">Seed Programs
                    </Link>
                    <Link :href="route('seedings.courses')" method="post" class="dropdown-item ">Seed Courses</Link>
                </div>
            </div>
        </div>
        <div v-else>
            <Link method="put" :preserve-state="false" :href="route('admin.courses.bulk-actions')"
                :data="{ course_status: 'Published', id }" class="btn  me-1 btn-primary btn-sm"><i
                class="fa fa-check me-1"></i>
            Publish</Link>
            <Link method="put" :preserve-state="false" :href="route('admin.courses.bulk-actions')"
                :data="{ course_status: 'Unpublished', id }" class="btn me-1  text-primary btn-sm"><i
                class="fa fa-times me-1"></i>
            Unpublish</Link>
            <Link method="put" :preserve-state="false" :href="route('admin.courses.bulk-actions', ['delete'])"
                :data="{ id }" class="btn btn-sm  me-1 text-danger "><i class="fa fa-trash me-1"></i>
            Trash</Link>
        </div>
    </div>
    <div class="card card-body" style="min-height:50vh">
        <div class="d-flex justify-content-between mb-3">
            <div class="col-md-3">
                <div class="input-group border border-secondary">
                    <input class="form-control border-0" type="search" autofocus placeholder="Search....">
                    <div class="input-group-append">
                        <button class="btn"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <pagination :data="courses" simple></pagination>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="text-nowrap small">
                    <tr>
                        <th style="width:10px">
                            <div class="form-check pt-0">
                                <input @change="selectAll($event)" class="form-check-input" type="checkbox">
                            </div>
                        </th>
                        <th style="width:100px">Course Code</th>
                        <th>Course Title</th>
                        <th>Course Program</th>
                        <th style="width:100px"></th>
                        <th>Modules</th>
                        <th>Skills</th>
                        <th style="width:150px">Created at</th>
                        <th style="width:150px">Last modified</th>
                        <th>Hits</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="courses.data.length">
                        <tr v-for="course in courses.data" :key="course.id">
                            <td>
                                <div class="form-check pt-0">
                                    <input class="form-check-input" type="checkbox" :value="course.id" v-model="id">
                                </div>
                            </td>
                            <td><span style="width:60px"
                                    class="badge badge-primary rounded-0">{{ course.course_code }}</span></td>
                            <td class="small text-uppercase">
                                <Link :href="route('admin.course.edit', [course.id])">{{ course.course_name }}</Link>
                            </td>
                            <td class="small">
                                {{ course.program_name ?? '----' }}
                            </td>
                            <td class="small text-nowrap">
                                <Link preserve-scroll v-if="course.course_status == 'Published'"
                                    class="btn p-0 px-1 btn-sm alert-success" :href="route('admin.courses.bulk-actions')"
                                    :data="{ course_status: 'Unpublished', id: [course.id] }" method="put"><i
                                    class="fa fa-check-circle me-2 text-success"></i>Published
                                </Link>

                                <Link preserve-scroll v-else class="btn p-0 px-1 btn-sm alert-danger"
                                    :href="route('admin.courses.bulk-actions')"
                                    :data="{ course_status: 'Published', id: [course.id] }" method="put"><i
                                    class="fa fa-times-circle me-2  text-danger"></i>Unpublished</Link>
                            </td>
                            <td class="text-center">{{ course.resource_by_topics.length }}</td>
                            <td class="text-center">{{ course.resource_by_skills.length }}</td>
                            <td class="small">{{ moment(course.created_at).format('lll') }}</td>
                            <td class="small">
                                <em>{{ moment(course.updated_at).fromNow() }}</em>
                            </td>
                            <td class="text-center">{{ course.hits }}</td>
                        </tr>
                    </template>
                    <tr v-else>
                        <td class="fs-6" colspan="10"><em>You do not have any course on the platform.
                                <Link class="text-primary" :href="route('admin.course.create')">Click here</Link> to get
                                started
                            </em></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            <pagination :data="courses"></pagination>
        </div>
    </div>
</template>