<script setup>
import { computed, onMounted, ref } from "vue";
import ResourceItem from "./ResourceItem.vue";
import { truncate } from 'lodash'
import draggable from 'vuedraggable'

let props = defineProps( {
    modelValue: Object,
    label: {
        type: String, default: 'Module Activity'
    },
} );
let dragOptions = computed( () => ( {
    animation: 200, ghostClass: "ghost"
} ) );
let reorder = () => {

    local_state.value.forEach( ( item, index ) => ( item.order = index ) )
}
let local_state = ref( props.modelValue.sort( ( a, b ) => a.order - b.order ) );
let addActivity = ( ev ) => {
    let id = props.modelValue.length + 1;
    local_state.value.push( {
        id,
        order: id,
        title: `${ props.label == 'Skillset' ? 'Skills ' : 'Week ' } ${ id }`, resources_list: []
    } )
}
let removeActivity = ( ev ) => {
    local_state.value.splice( ev, 1 );
}
let addResource = ( index ) => {
    let rl = local_state.value[ index ].resources_list;
    let id = rl.length + 1;
    rl.push( {
        id, title: `Resource ${ id }`, type: 'Books'
    } )
}
let removeResource = ( resource, index ) => {
    local_state.value[ index ].resources_list.splice( resource, 1 )
}
</script>

<template>
    <div class="accordion" id="activity">
        <draggable @change="reorder" tag="div" v-model="local_state" item-key="id" v-bind="dragOptions">
            <template #item="{ element, index }">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" :class="{ collapsed: index !== local_state.length - 1 }"
                            type="button" data-bs-toggle="collapse" :data-bs-target="`#_${element.id}`">
                            <i class="fas fa-arrow-up-right-dots me-2   "></i>{{ truncate(element.title, { length: 50 }) }}
                        </button>
                    </h2>
                    <div :id="`_${element.id}`" class="accordion-collapse collapse"
                        :class="{ show: index == local_state.length - 1 }" data-bs-parent="#activity">
                        <div class="accordion-body">
                            <div class="form-floating mb-3">
                                <textarea style="height:70px;white-space: pre-wrap;" class="form-control"
                                    v-model="element.title"></textarea>
                                <label>{{ label }}</label>
                            </div>
                            <div v-if="element.resources_list.length > 0">
                                <h6>Resources List</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            <resource-item :model-value="res" v-for="(res, i) in element.resources_list"
                                                :key="res" @removeResource="removeResource(i, index)">
                                            </resource-item>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-link" @click.prevent="addResource(index)"><i
                                        class="fa fa-plus-square me-2"></i> Add
                                    Resource</button>
                            </div>
                            <button @click.prevent="removeActivity(index)" class="btn p-0 btn-link text-danger"><i
                                    class="fa fa-times me-1"></i>Remove
                                {{ label }}</button>
                        </div>
                    </div>
                </div>
            </template>
        </draggable>
    </div>
    <div v-if="label == 'Skillset'" class="text-center mt-2">
        <button @click.prevent="addActivity" class="btn  alert-primary"><i class="fa fa-plus"></i>
            Add Skills</button>
    </div>
    <button v-else @click.prevent="addActivity" class="btn btn-link btn-xs p-0"><i class="fa fa-plus"></i>
        Add Module Activity</button>
</template>