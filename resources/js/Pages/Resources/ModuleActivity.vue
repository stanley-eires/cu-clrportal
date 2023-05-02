<script setup>
import { ref } from "vue";
import ResourceItem from "@/Pages/Resources/ResourceItem.vue";
import { truncate } from 'lodash'

let props = defineProps( {
    modelValue: Object,
    label: {
        type: String, default: 'Module Activity'
    },
    index: Number
} );
let local_state = ref( props.modelValue );
let addResource = ( module_id, activity_id ) => {
    let id = local_state.value.resources_list.length + 1;
    local_state.value.resources_list.push( {
        id, title: `Resource ${ id }`, type: 'Books'
    } )
}
let removeResource = ( ev ) => {
    local_state.value.resources_list.splice( local_state.value.resources_list.findIndex( e => e.id == ev ), 1 );
}
</script>

<template>
    <div class="accordion-item" :class="{ collapsed: index != 0 }">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                :data-bs-target="`#_${local_state.id}`">
                <i class="fas fa-arrow-up-right-dots me-2   "></i>{{ truncate(local_state.title, { length: 50 }) }}
            </button>
        </h2>
        <div :id="`_${local_state.id}`" class="accordion-collapse collapse" :class="{ show: index == 0 }"
            data-bs-parent="#activity">
            <div class="accordion-body">
                <div class="form-floating mb-3">
                    <textarea style="height:70px;white-space: pre-wrap;" class="form-control"
                        v-model="local_state.title"></textarea>
                    <label>{{ label }}</label>
                </div>
                <div v-if="local_state.resources_list.length > 0">
                    <h6>Resources List</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <resource-item :model-value="res" v-for="(res) in local_state.resources_list" :key="res.id"
                                    @removeResource="removeResource($event)">
                                </resource-item>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-link" @click.prevent="addResource"><i class="fa fa-plus-square me-2"></i> Add
                        Resource</button>
                </div>
                <button @click.prevent="$emit('removeActivity', modelValue.id)" class="btn p-0 btn-link text-danger"><i
                        class="fa fa-times me-1"></i>Remove
                    {{ label }}</button>
            </div>
        </div>
    </div>
</template>