<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import ResourceByTopic from '@/Components/ResourceByTopic.vue';
import ResourceBySkill from '@/Components/ResourceBySkill.vue';
import { groupBy } from 'lodash'
import { computed, ref } from 'vue';

let props = defineProps( {
    course: Object, programs: Object, title: String
} )


let dept_by_program = computed( () => {
    return groupBy( props.programs, ( e ) => e.department_name )
} )
let banner = ref( props.course.course_banner ? `/storage/${ props.course.course_banner }` : null );
let form = useForm( {
    course_name: props.course.course_name,
    course_code: props.course.course_code,
    course_status: props.course.course_status ?? 'Unpublished',
    course_program: props.course.course_program,
    course_banner: props.course.course_banner,
    course_overview: props.course.course_overview,
    resource_by_topics: props.course.resource_by_topics ?? [],
    resource_by_skills: props.course.resource_by_skills ?? [],
} );
let handleSubmit = ( action ) => {
    let form_ele = document.querySelector( 'form' );
    if ( form_ele.checkValidity() === false ) {
        form_ele.reportValidity();
        return;
    }
    form.transform( ( data ) => ( {
        ...data, action
    } ) ).post( route( 'admin.course.save', [ props.course.id ?? null ] ), { preserveState: true, only: [ 'course', 'flash' ] } )
}
let addActivity = ( ev ) => {
    let id = form.resource_by_skills.length + 1;
    form.resource_by_skills.push( {
        id,
        title: `Skills ${ id }`, resources_list: []
    } )
}
let removeActivity = ( ev ) => {
    form.resource_by_skills.splice( form.resource_by_skills.findIndex( e => e.id == ev ), 1 );
}
let bannerChange = ( ev ) => {
    form.course_banner = ev.target.files[ 0 ];
    let reader = new FileReader();
    reader.onload = () => {
        banner.value = reader.result
    };
    reader.readAsDataURL( ev.target.files[ 0 ] )
}

</script>

<template>
    <div class="mb-3   py-2 card-header">
        <button type="submit" @click.prevent="handleSubmit('save')" class="btn btn-primary btn-sm  me-1"> <i
                class="fa fa-save  me-1  "></i>
            Save</button>
        <button type="submit" @click.prevent="handleSubmit('close')"
            class="d-none d-md-inline btn me-1 btn-sm  border-primary text-primary "><i class="fa fa-check me-1"></i> Save &
            Close</button>
        <button type="submit" @click.prevent="handleSubmit('new')"
            class="d-none d-md-inline btn me-1 border-primary text-primary btn-sm"><i class="fa fa-plus me-1"></i> Save &
            New</button>
        <button v-if="course.id" type="submit" @click.prevent="handleSubmit('copy')"
            class="btn me-1 border-primary text-primary btn-sm "><i class="fa fa-copy me-1"></i> Save as
            Copy</button>
        <Link as="button" :href="route('admin.courses')" class="btn border me-1 border-primary text-primary "><i
            class="fa fa-times-circle me-1"></i> Close</Link>
    </div>
    <form class="row">
        <div class="col-lg-8">
            <div class="row ">
                <div class="col-lg-9">
                    <div class="form-floating mb-3">
                        <input v-model="form.course_name" required class="form-control">
                        <label>Course Name *</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-floating mb-3">
                        <input v-model="form.course_code" type="text" maxlength="6" class="form-control">
                        <label>Course Code</label>
                    </div>
                </div>
                <div class="col-lg-12">
                    <ul class="nav nav-tabs separator-tabs mb-2">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#content"
                                type="button">Content</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#res-topic" type="button">Resource
                                By Topics</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#res-skill" type="button">Resource
                                By Skills</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="content">
                            <div class="form-floating mb-3">
                                <textarea style="height:100px" maxlength="250" class="form-control"
                                    v-model="form.course_overview"></textarea>
                                <label class="form-label">Course Overview</label>
                            </div>
                        </div>
                        <div class="tab-pane " id="res-topic">
                            <ResourceByTopic :resource="form.resource_by_topics"></ResourceByTopic>
                        </div>
                        <div class="tab-pane " id="res-skill">
                            <ResourceBySkill :resource="form.resource_by_skills"></ResourceBySkill>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-4">
            <div class="form-floating mb-3">
                <select class="form-control form-select " v-model="form.course_status">
                    <option v-for="i in ['Published', 'Unpublished']" :value="i" :key="i">{{ i }}</option>
                </select>
                <label>Status</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-control" v-model="form.course_program">
                    <optgroup :label="dept" v-for="dept in Object.keys(dept_by_program).sort()" :key="dept">
                        <option :value="i.id" v-for="i in dept_by_program[dept]" :key="i.id">
                            {{ i.program_name }}
                        </option>
                    </optgroup>
                </select>
                <label>Program</label>
            </div>
            <div v-if="form.course_banner" class="d-flex align-items-center alert-light rounded-0  p-3">
                <img class="me-2 img-thumbnail rounded-1" :src="banner" style="width:200px;height:150px;object-fit:cover">
                <div class="media-body">
                    <p class="mb-0 fs-6 text-dark">Course Banner</p>
                    <button @click.prevent="form.course_banner = null" class="btn px-0 btn-sm btn-link "><i
                            class="fa fa-times-circle me-1"></i>
                        Remove Banner</button>
                </div>
            </div>
            <div class="mb-3" v-else>
                <label class="small d-block">Course Banner</label>
                <input type="file" class="small" accept="image/*" @change="bannerChange">
            </div>
        </div>
    </form>
</template>
<style >
.accordion-button:not(.collapsed) {
    color: unset;
    background-color: unset;
    box-shadow: none
}

.flip-list-move {
    transition: transform 0.5s;
}

.no-move {
    transition: transform 0s;
}

.ghost {
    opacity: 0.5;
    border: 2px dotted;
    background: #1c0625;
}
</style>