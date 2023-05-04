<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { truncate } from 'lodash'
import Pagination from '@/Components/Pagination.vue';
import { onMounted } from 'vue';
let props = defineProps( {
    courses: Object, programs: Object, title: String
} )
let form = useForm( {
    search: null,
    programs: [],
} );
let handleFilter = () => {
    form.get( route( 'public.courses' ), { preserveState: true, only: [ 'courses' ] } )
}
onMounted( () => {
    let query = new URLSearchParams( window.location.search );
    form.search = query.get( 'search' );
    form.programs = query.getAll( 'programs[]' );

} )
let clearFilters = () => {
    form.search = null;
    form.programs = [];
    handleFilter();
}
</script>
<template>
    <Head :title="title" />
    <div class="container my-5">
        <div class="row gx-5">
            <div class="col-lg-3">
                <div class="offcanvas offcanvas-bottom" tabindex="-1" id="Id2">
                    <div class="offcanvas-header">
                        <div class="form-group w-100">
                            <label for="">Search by keyword</label>
                            <div class="input-group border border-secondary shadow-sm align-items-center">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-0 pe-0"><i
                                            class="fa fa-search fs-6"></i></span>
                                </div>
                                <input @keydown.enter="handleFilter" type="search" v-model="form.search" autofocus
                                    class="form-control border-0 form-control-lg" placeholder="Course, Course Code">
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div>
                            <button v-if="form.search || form.programs.length > 0" @click.prevent="clearFilters"
                                class="btn mb-2"><i class="fas fa-times-circle    "></i> Clear
                                All Filters</button>
                            <fieldset>
                                <label for="">Filter by Program</label>
                                <div class="form-group mb-3">
                                    <div class="form-check" v-for="i in programs" :key="i.id">
                                        <input @change="handleFilter" class="form-check-input" type="checkbox"
                                            :id="`_${i.id}`" :value="i.id" v-model="form.programs">
                                        <label class="form-check-label d-flex justify-content-between" :for="`_${i.id}`">

                                            <span>{{ i.program_name }}</span>
                                            <span class="mx-3">({{ i.courses_count }})</span>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="d-none d-lg-block sticky-sidebar">
                    <div class="form-group mb-3">
                        <label for="">Search by keyword</label>
                        <div class="input-group border border-secondary shadow-sm align-items-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-0 pe-0"><i
                                        class="fa fa-search fs-6"></i></span>
                            </div>
                            <input @keydown.enter="handleFilter" type="search" v-model="form.search" autofocus
                                class="form-control border-0 form-control-lg" placeholder="Course, Course Code">
                        </div>
                    </div>
                    <button v-if="form.search || form.programs.length > 0" @click.prevent="clearFilters" class="btn mb-2"><i
                            class="fas fa-times-circle"></i> Clear
                        All Filters</button>
                    <fieldset>
                        <label for="">Filter by Program</label>
                        <div class="form-group mb-3">
                            <div class="form-check" v-for="i in programs" :key="i.id">
                                <input @change="handleFilter" class="form-check-input" type="checkbox" :id="`_${i.id}`"
                                    :value="i.id" v-model="form.programs">
                                <label class="form-check-label d-flex justify-content-between" :for="`_${i.id}`">

                                    <span>{{ i.program_name }}</span>
                                    <span class="mx-3">({{ i.courses_count }})</span>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="py-0" v-if="courses.data.length">
                    <div class='d-flex align-items-center justify-content-between'>
                        <button class="btn btn-outline-primary text-nowrap mb-2 d-lg-none btn-sm" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#Id2">
                            <i class="fa fa-search"></i> Search
                        </button>
                        <div class='w-100 d-flex justify-content-end'>
                            <pagination :data="courses" simple></pagination>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6" v-for="course in courses.data" :key="course.id">
                            <div class="card  h-100 shadow">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img :src="course.course_banner ? `/storage/${course.course_banner}` : '/assets/images/course_cover.jpg'"
                                                :alt="course.course_name" style="width:70px;height:70px;object-fit: cover;">
                                            <span
                                                class="rounded-0 d-block border badge btn-primary ">{{ course.course_code }}</span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="position-relative">
                                                <Link class="stretched-link d-block fw-bold"
                                                    :href="route('public.course.single', [course.id])">
                                                {{ course.course_name }}
                                                </Link>
                                                <p class="small">
                                                    {{ truncate(course.course_overview, { length: 200 }) }}
                                                </p>
                                            </div>
                                            <div class="">
                                                <Link :href="route('public.courses', [{ 'programs[]': course.program_id }])"
                                                    class="badge text-wrap d-inline-block rounded text-white badge-dark">
                                                <i class="fas fa-award me-1"></i> {{ course.program_name }}</Link>
                                                <small
                                                    class="small badge text-wrap text-dark px-0 mx-1">Undergraduate</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <pagination :data="courses"></pagination>
                    </div>
                </div>
                <div v-else class="jumbotron-fluid text-center">
                    <h1 class="display-4 text-primary fw-bold">We couldn't find anything !!!</h1>
                    <p class="lead">How about we search with a different parameter?</p>
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
    top: 100px;
    overflow: auto;
}
</style>





