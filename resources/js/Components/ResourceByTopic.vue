<script setup>
import { computed, ref } from 'vue';
import draggable from 'vuedraggable'
import ModuleBlock from './ModuleBlock.vue'
let props = defineProps( {
    resource: Object
} )
let dragOptions = computed( () => ( {
    animation: 200, ghostClass: "ghost"
} ) );


let local_state = ref( props.resource.sort( ( a, b ) => a.order - b.order ) );
let addModule = () => {
    let id = local_state.value.length + 1;
    local_state.value.push( {
        id,
        order: id,
        title: `Module ${ id }`,
        module_activities: []
    } )
    setTimeout( () => {
        document.getElementById( `modules_resource_by_topics_${ id }` ).scrollIntoView( true )
    } );

}
let removeModule = ( ev ) => {
    local_state.value.splice( local_state.value.findIndex( e => e.id == ev ), 1 );
}
let reorder = () => {
    local_state.value.forEach( ( item, index ) => ( item.order = index ) )
}
</script>
<template>
    <div class="accordion" id="modules_resource_by_topics">
        <draggable @change="reorder" tag="div" v-model="local_state" item-key="id" v-bind="dragOptions">
            <template #item="{ element, index }">
                <div class="accordion-item">
                    <div class="accordion-header">
                        <button class="accordion-button" :class="{ collapsed: index !== local_state.length - 1 }"
                            type="button" data-bs-toggle="collapse"
                            :data-bs-target="`#modules_resource_by_topics_${element.id}`">
                            <i class="fa fa-folder-open me-2"></i>{{ element.title }}
                        </button>
                    </div>
                    <div :id="`modules_resource_by_topics_${element.id}`" class="accordion-collapse collapse"
                        :class="{ show: index == local_state.length - 1 }" data-bs-parent="#modules_resource_by_topics">
                        <div class="accordion-body">
                            <ModuleBlock :model-value="element" @removeModule="removeModule"></ModuleBlock>
                        </div>
                    </div>
                </div>
            </template>
        </draggable>
    </div>
    <div class="d-flex justify-content-center my-3">
        <button @click.prevent="addModule()" class="btn alert-primary"><i class="fa fa-plus"></i>
            Add Module</button>
    </div>
</template>