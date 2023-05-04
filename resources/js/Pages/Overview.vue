<script setup>
import { Link } from "@inertiajs/vue3";
import CounterUp from '@/Components/CounterUp.vue';
import VueApexCharts from "vue3-apexcharts";
import moment from 'moment';
import ProfilePicture from '@/Components/ProfilePicture.vue';

let props = defineProps( { title: String, data: Object } );
const maintenance_functions = [
    { type: 'clear_cache', name: 'Clear Data Caches', destructive: false, },
    { type: 'optimize', name: 'Optimize Site', destructive: false, },
    { type: 'create_symlink', name: 'Create Symlink', destructive: false },
    { type: 'reset_platform', name: 'Reset Platform', destructive: true } ]
</script>
<template>
    <div class="row g-lg-4">
        <div class="col-lg-8">
            <div class="row g-lg-4">
                <div class="col-lg-4">
                    <div class="card card-body shadow text-center">
                        <i class="fas fa-box-archive mb-2 fa-3x text-success"></i>
                        <p class="mb-0 fw-bold">Departments</p>
                        <p class="display-6  mb-0"><counter-up :number="data.departments"></counter-up></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <Link :href="route('admin.programs')" class="card card-body shadow text-center">
                    <i class="fas fa-folder mb-2 fa-3x text-primary"></i>
                    <p class="mb-0 fw-bold">Programs</p>
                    <p class="display-6  mb-0"><counter-up :number="data.programs"></counter-up></p>
                    </Link>
                </div>
                <div class="col-lg-4">
                    <Link :href="route('admin.courses')" class="card card-body shadow text-center">
                    <i class="fas fa-file-lines mb-2 fa-3x text-warning"></i>
                    <p class="mb-0 fw-bold">Courses</p>
                    <p class="display-6  mb-0"><counter-up :number="data.courses"></counter-up></p>
                    </Link>
                </div>
                <div class="col-lg-12">
                    <div class="card shadow card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs separator-tabs mb-2" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#program_views"
                                    type="button" role="tab">Top 10 Viewed Programs</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#course_view" type="button"
                                    role="tab">Top 10 Viewed
                                    Courses</button>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="program_views" role="tabpanel" aria-labelledby="home-tab">
                                <VueApexCharts height="500px" type="bar" :options="{
                                    plotOptions: {
                                        bar: {
                                            barHeight: '100%',
                                            distributed: true,
                                            horizontal: false,
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false,
                                    },
                                    xaxis: { categories: data.program_by_view.map(e => e.program_name) },
                                    legend: {
                                        show: false
                                    }
                                }"
                                    :series="[{ name: 'Total View', data: data.program_by_view.map(e => e.program_hits) }]">
                                </VueApexCharts>
                            </div>
                            <div class="tab-pane" id="course_view" role="tabpanel">
                                <VueApexCharts height="500px" type="bar" :options="{
                                    plotOptions: {
                                        bar: {
                                            barHeight: '100%',
                                            distributed: true,
                                            horizontal: true,
                                            dataLabels: {
                                                position: 'bottom'
                                            },
                                        }
                                    },
                                    dataLabels: {
                                        enabled: true,
                                        textAnchor: 'start',
                                        style: {
                                            colors: ['#fff']
                                        },
                                        formatter: (val, opt) => `${opt.w.globals.labels[opt.dataPointIndex]}: (${val} views)`,
                                        offsetX: 0,
                                        dropShadow: {
                                            enabled: true
                                        }
                                    },
                                    xaxis: { categories: data.courses_by_view.map(e => e.course_name) },
                                    yaxis: {
                                        labels: {
                                            show: false
                                        }
                                    },
                                }" :series="[{ name: 'Total View', data: data.courses_by_view.map(e => e.hits) }]">
                                </VueApexCharts>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body shadow  mb-3">
                <h3><i class="fas fa-user-group me-1"></i>Users on Platform (Ratio)</h3>
                <VueApexCharts type="pie" :options="{
                    legend: {
                        position: 'bottom'
                    },
                    labels: data.users_by_group.map(e => e.user_group)
                }" :series="data.users_by_group.map(e => e.total_count)">
                </VueApexCharts>
            </div>
            <div class="card card-body shadow  mb-3">
                <h3><i class="fas fa-sign-in me-1"></i> Recent Login</h3>
                <table class="table table-sm small">
                    <thead>
                        <tr>
                            <th>Users</th>
                            <th>Last login</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="i in data.users_login" :key="i">
                            <td class="text-uppercase">
                                <div class="d-flex position-relative align-items-center">
                                    <div class="flex-shrink-0">
                                        <ProfilePicture size="25">
                                        </ProfilePicture>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        {{ i.name }}
                                    </div>
                                </div>
                            </td>
                            <td><em>{{ i.login_at ? moment(i.login_at).fromNow() : "--" }}</em></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card card-body shadow">
                <h5 class="card-title mb-3"><i class="fas fa-screwdriver-wrench me-1"></i> Maintenance Functions</h5>
                <ul class="list-unstyled row">
                    <li class="col-6" v-for="func in maintenance_functions" :key="func">
                        <Link v-if="func.destructive"
                            onclick="return prompt('This is a destructive action and should only be done by someone who knows the consequences.\n\nEnter: I UNDERSTAND in the input field below to continue') == 'I UNDERSTAND'"
                            class="btn btn-sm text-danger" method="post" :data="{ type: func.type }"
                            :href="route('admin.maintenance-functions')"><i class="fas fa-skull-crossbones  me-1"></i>
                        {{ func.name }}</Link>
                        <Link v-else class="btn btn-sm" method="post" :data="{ type: func.type }"
                            :href="route('admin.maintenance-functions')"><i class="fa fa-chevron-right  me-1"></i>
                        {{ func.name }}</Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>