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
        <td style="width:70%">
            <div class="form-floating">
                <textarea type="text" class="form-control" v-model="local_state.title"></textarea>
                <label>Resource</label>
            </div>
        </td>
        <td>
            <div class="form-floating">
                <select class="form-control form-select " v-model="local_state.type">
                    <option
                        v-for="(i) in ['Books', 'Articles', 'Newspapers & Industry report', 'Teaching methods', 'Learning methods', 'Tools', 'Techniques']"
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