<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import ResourcesTabbed from '@/Components/ResourcesTabbed.vue';
import { kebabCase } from 'lodash'

let props = defineProps( {
    course: Object, title: String
} )
let smoothScroll = ( id ) => {
    const yOffset = -150;
    const element = document.getElementById( id );
    const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
    window.scrollTo( { top: y, behavior: 'smooth' } );

}
</script>

<template>
    <Head :title="title" />
    <div class="mb-5 px-5 d-flex align-items-center justify-content-center mast-head text-center"
        :style="{ 'background-image': course.course_banner ? `url('/storage/${course.course_banner}')` : `url(/assets/images/course_cover.jpg)` }">
        <h1 class="text-white mb-0 display-6 fw-bold text-uppercase">{{ course.course_name }}</h1>
    </div>
    <nav class="breadcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <Link :href="route('public.courses')">Courses</Link>
                </li>
                <li class="breadcrumb-item">{{ course.program_name }}</li>
                <li class="breadcrumb-item active">{{ course.course_name }}</li>
            </ol>
        </div>
    </nav>
    <div class="container ">
        <div class="row gx-5">
            <div class="col-lg-3 mb-3">
                <div class="sticky-sidebar">
                    <nav class="nav flex-row flex-lg-column  ">
                        <a class="nav-link " href="#" @click.prevent="smoothScroll('overview')"><i
                                class="fas fa-long-arrow-right me-2"></i>Overview</a>
                        <a v-if="course.resource_by_topics.length > 0" @click.prevent="smoothScroll('resource-by-topic')"
                            class="nav-link " href="#"><i class="fas fa-long-arrow-right me-2"></i>Resources By Topics</a>
                        <a v-if="course.resource_by_skills.length > 0" @click.prevent="smoothScroll('resource-by-skill')"
                            class="nav-link" href="#"><i class="fas fa-long-arrow-right me-2"></i>Resources By Skills</a>
                    </nav>
                </div>
            </div>
            <div class="col-lg-9" id="overview">

                <h3 class=" font-weight-bold lead">{{ course.course_name }}</h3>
                <p>{{ course.course_overview }} </p>
                <div class="accordion" id="resources-type">
                    <div class="accordion-item" v-if="course.resource_by_topics.length > 0">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#resource-by-topic">
                                RESOURCES BY TOPICS
                            </button>
                        </h2>
                        <div id="resource-by-topic" class="accordion-collapse collapse show"
                            data-bs-parent="#resources-type">
                            <div class="accordion-body">
                                <div class="accordion" id="ResourceByTopicAccordion">
                                    <div class="accordion-item"
                                        v-for="(_module, m_index) in course.resource_by_topics.sort((x, y) => x.order - y.order)"
                                        :key="_module.id">
                                        <h4 class="accordion-header">
                                            <button class="accordion-button" :class="{ collapsed: m_index != 0 }"
                                                type="button" data-bs-toggle="collapse"
                                                :data-bs-target="`#module-${kebabCase(_module.title)}`">
                                                <i class="fa fa-folder-open me-2"></i>{{ _module.title }}
                                            </button>
                                        </h4>
                                        <div :id="`module-${kebabCase(_module.title)}`"
                                            class="accordion-collapse collapse mb-5" :class="{ show: m_index == 0 }"
                                            data-bs-parent="#ResourceByTopicAccordion">
                                            <div class="accordion-body">
                                                <div class="accordion" :id="`module-${kebabCase(_module.title)}-activity`">
                                                    <div class="accordion-item"
                                                        v-for="(activity, a_index) in _module.module_activities"
                                                        :key="activity.id">
                                                        <h5 class="accordion-header">
                                                            <button class="accordion-button fst-italic align-items-start"
                                                                :class="{ collapsed: a_index != 0 }" type="button"
                                                                data-bs-toggle="collapse"
                                                                :data-bs-target="`#activity-${kebabCase(activity.title)}`">
                                                                <i class="fas fa-arrow-up-right-dots me-2   "></i>
                                                                <span
                                                                    style="white-space: pre-wrap;">{{ activity.title }}</span>
                                                            </button>
                                                        </h5>
                                                        <div :id="`activity-${kebabCase(activity.title)}`"
                                                            class="accordion-collapse collapse mb-5"
                                                            :class="{ show: a_index == 0 }"
                                                            :data-bs-parent="`#module-${kebabCase(_module.title)}-activity`">
                                                            <div class="accordion-body">
                                                                <resources-tabbed
                                                                    :resource="activity.resources_list"></resources-tabbed>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item" v-if="course.resource_by_skills.length > 0">
                        <div class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse"
                                data-bs-target="#resource-by-skill">
                                RESOURCES BY SKILLS
                            </button>
                        </div>
                        <div id="resource-by-skill" class="accordion-collapse collapse show"
                            data-bs-parent="#resources-type">
                            <div class="accordion-body">
                                <div class="accordion" id="rbs">
                                    <div class="accordion-item"
                                        v-for="(activity, a_index) in course.resource_by_skills.sort((x, y) => x.order - y.order)"
                                        :key="activity.id">
                                        <h5 class="accordion-header">
                                            <button class="accordion-button align-items-start"
                                                :class="{ collapsed: a_index != 0 }" type="button" data-bs-toggle="collapse"
                                                :data-bs-target="`#activity-${kebabCase(activity.title)}`">
                                                <i class="fas fas fa-arrow-up-right-dots me-2   "></i>{{ activity.title }}
                                            </button>
                                        </h5>
                                        <div :id="`activity-${kebabCase(activity.title)}`"
                                            class="accordion-collapse collapse mb-5" :class="{ show: a_index == 0 }"
                                            data-bs-parent="#rbs">
                                            <div class="accordion-body">
                                                <resources-tabbed :resource="activity.resources_list"></resources-tabbed>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
.mast-head {
    height: 20vh;
    background-color: #000000ec;
    background-size: contain;
    background-position: center;
    background-repeat: repeat;
    background-blend-mode: overlay;
}


@media (min-width:767px) {
    .sticky-sidebar {
        height: auto;
        position: -webkit-sticky;
        position: sticky;
        top: 110px;
    }

    .sticky-sidebar .nav-link {
        border: 1px solid black;
        margin-bottom: .5rem;
        color: black;

    }
}

.accordion-button:not(.collapsed) {
    color: unset;
    background-color: unset;
    box-shadow: none
}
</style>
