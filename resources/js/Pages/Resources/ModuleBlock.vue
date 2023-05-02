<script setup>
import ModuleActivity from "@/Pages/Resources/ModuleActivity.vue";
import { ref } from "vue";
let props = defineProps( {
    modelValue: {
        type: Object,
        required: true,
    },
} );
let addActivity = ( ev ) => {
    let id = local_state.value.module_activities.length + 1;
    local_state.value.module_activities.push( {
        id,
        title: `Week ${ id }`, resources_list: []
    } )
}
let removeActivity = ( ev ) => {
    local_state.value.module_activities.splice( local_state.value.module_activities.findIndex( e => e.id == ev ), 1 );
}
let local_state = ref( props.modelValue );
</script>

<template>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" v-model="local_state.title">
        <label>Module Title</label>
    </div>
    <div class="accordion" id="activity">
        <module-activity :model-value="resource" :id="`res-topic-${resource.id}`"
            v-for="(resource, index) in local_state.module_activities" :index="index" :key="resource.id"
            @removeActivity="removeActivity"></module-activity>
    </div>
    <button @click.prevent="addActivity" class="btn btn-link btn-xs p-0"><i class="fa fa-plus"></i>
        Add Module Activity</button>
    <div class="d-flex justify-content-center">
        <button @click.prevent="$emit('removeModule', modelValue.id)" class="btn text-danger"><i class="fa fa-times me-1"
                aria-hidden="true"></i> Remove Module</button>
    </div>
</template>