<script setup>
import { Head, Link } from '@inertiajs/vue3';
import * as linkify from 'linkifyjs';
import linkifyHtml from 'linkify-html';
import ResourcesTabbed from '@/Components/ResourcesTabbed.vue';
import { kebabCase } from 'lodash'

let props = defineProps( {
    course: Object, title: String
} )
</script>

<template>
    <Head :title="title" />
    <div class="mb-2 px-5 d-flex flex-column align-items-center justify-content-center mast-head text-center"
        :style="{ 'background-image': course.course_banner ? `url('/storage/${course.course_banner}')` : `url(/assets/images/syllabus.png)` }">
        <h1 class="text-white mb-0 display-6 fw-bold text-uppercase">{{ course.course_name }}</h1>
        <h3 class="text-white">{{ course.course_code }}</h3>
    </div>
    <nav class="breadcrumb">
        <div class="container-fluid">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <Link :href="route('public.courses')">Courses</Link>
                </li>
                <li class="breadcrumb-item">{{ course.program_name }}</li>
                <li class="breadcrumb-item active">{{ course.course_name }}</li>
            </ol>
        </div>
    </nav>
    <div class="container-fluid">
        <section class="mb-3">
            <h6 class="text-bg-dark p-2">RESOURCES BY TOPICS</h6>
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr v-for="_module in course.resource_by_topics.sort((x, y) => x.order - y.order)" :key="_module.id">
                        <td style="width:200px">{{ _module.title }}</td>
                        <td>
                            <table class="table ">
                                <tbody>
                                    <tr class="table-bordered" v-for="(activity) in _module.module_activities"
                                        :key="activity.id">
                                        <td style="width:200px">{{ activity.title }}</td>
                                        <td>
                                            <ol>
                                                <li v-for="(resource) in activity.resources_list" :key="resource.id">
                                                    <strong class="me-1 text-secondary">[{{ resource.type }}]</strong><span
                                                        v-html="linkifyHtml(resource.title, { defaultProtocol: 'https' })"></span>
                                                </li>
                                            </ol>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class="mb-3">
            <h6 class="text-bg-dark p-2">RESOURCES BY SKILLS</h6>
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr v-for="_module in course.resource_by_skills.sort((x, y) => x.order - y.order)" :key="_module.id">
                        <td style="width:200px">{{ _module.title }}</td>
                        <td>
                            <ol>
                                <li v-for="(resource) in _module.resources_list" :key="resource.id">
                                    <strong class="me-1 text-secondary">[{{ resource.type }}]</strong><span
                                        v-html="linkifyHtml(resource.title, { defaultProtocol: 'https' })"></span>
                                </li>
                            </ol>
                        </td>

                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</template>

<style scoped>
.mast-head {
    height: 15vh;
    background-color: #000000ec;
    background-size: contain;
    background-position: center;
    background-repeat: repeat;
    background-blend-mode: overlay;
}

.table td,
.table,
.table th {
    border-color: black !important
}
</style>
