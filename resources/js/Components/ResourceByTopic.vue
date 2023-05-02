<script setup>
import ModuleBlock from '@/Pages/Resources/ModuleBlock.vue';
let props = defineProps( {
    resource: Object
} )
let local_state = props.resource;
let addModule = () => {
    let id = local_state.length + 1;
    local_state.push( {
        id,
        title: `Module ${ id }`,
        module_activities: []
    } )
    setTimeout( () => {
        document.getElementById( `modules_resource_by_topics_${ id }` ).scrollIntoView( true )
    } );

}
let removeModule = ( ev ) => {
    local_state.splice( local_state.findIndex( e => e.id == ev ), 1 );
}
</script>
<template>
    <div class="accordion" id="modules_resource_by_topics">
        <div class="accordion-item" v-for='(mod, index) in resource' :key="mod.id">
            <h2 class="accordion-header " :class="{ collapsed: index != 0 }">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    :data-bs-target="`#modules_resource_by_topics_${mod.id}`">
                    <i class="fa fa-folder-open me-2"></i>{{ mod.title }}
                </button>
            </h2>
            <div :id="`modules_resource_by_topics_${mod.id}`" class="accordion-collapse collapse"
                :class="{ show: index == 0 }" data-bs-parent="#modules_resource_by_topics">
                <div class="accordion-body">
                    <ModuleBlock :model-value="mod" @removeModule="removeModule"></ModuleBlock>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center my-3">
        <button @click.prevent="addModule()" class="btn alert-primary"><i class="fa fa-plus"></i>
            Add Module</button>
    </div>
</template>