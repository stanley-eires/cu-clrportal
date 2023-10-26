<script setup>
import { ref } from "vue";

let props = defineProps( {
    modelValue: {
        type: Object,
        required: true,
    },
} );
let local_state = ref( props.modelValue );
</script>

<template>
    <tr>
        <td style="width:60%">
            <div class="form-floating">
                <textarea type="text" class="form-control" v-model="local_state.title"></textarea>
                <label>Resource</label>
            </div>
        </td>
        <td style="min-width:150px">
            <div class="form-floating">
                <select class="form-select" v-model="local_state.type">
                    <option value="">--Choose Type--</option>
                    <option
                        v-for="(i) in ['Books', 'Journal Articles', 'Newspaper Articles', 'Industry Reports', 'Workshops & Trainings', 'Software & Tools']"
                        :key="i">{{ i }}</option>
                </select>
                <label>Type</label>
            </div>
        </td>
        <td class="text-center" style="vertical-align: middle;">
            <button @click.prevent="$emit('removeResource', local_state.id)" class="btn p-0"><i
                    class="fa fa-trash text-danger"></i></button>
        </td>
    </tr>
</template>