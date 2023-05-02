
<script setup>
import { computed, onMounted } from "vue"
import { nanoid } from 'nanoid'
import { kebabCase } from 'lodash'
import * as linkify from 'linkifyjs';
import linkifyHtml from 'linkify-html';

let props = defineProps( {
    resource: Object
} )
let resource_type = computed( () => {
    return new Set( props.resource.map( e => e.type ).sort() )
} )
let comp_id = computed( () => {
    return nanoid( 5 )
} )
</script>
<template>
    <div class="row g-4" v-if="resource.length > 0">
        <div class="col-md-3">
            <ul class="nav nav-pills flex-row flex-md-column nav-fill">
                <li class="nav-item " role="presentation" v-for="(title, index) in resource_type" :key="index">
                    <button class="nav-link text-md-start" :class="{ active: index == 0 }" data-bs-toggle="tab"
                        :data-bs-target="`#_${comp_id}${kebabCase(title)}`" type="button" role="tab">{{ title }}</button>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="tab-content card card-body h-100 shadow-sm border">
                <div class="tab-pane" :id="`_${comp_id}${kebabCase(title)}`" :class="{ active: index == 0 }"
                    v-for="(title, index) in resource_type" :key="index">
                    <h6 class="fw-bold">{{ title }}</h6>
                    <ol>
                        <li v-for="(res, rxindex) in resource.filter(e => e.type == title)" :key="rxindex"
                            v-html="linkifyHtml(res.title, { defaultProtocol: 'https' })">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</template>
