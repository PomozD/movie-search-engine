<template>
    <div>
        <input v-model.trim="country" @input="emitInput" :placeholder="placeholder" type="text" class="input__editInfo"/>
        <button @click="removeInput" v-if="showRemoveButton">Удалить</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            country: '',
        };
    },
    props: {
        placeholder: String,
        showRemoveButton: Boolean
    },
    methods: {
        emitInput() {
            this.$emit('update:country', this.country);
        },
        removeInput() {
            this.$emit('remove'); // Эмитируем событие remove при нажатии кнопки удалить
        }
    },
};
</script>

<style>
.input__editInfo {
    border: 2px solid black;
    height: 35px;
    width: 310px;
    padding-left: 20px;
    color: black;
    font-family: "Helvetica Regular";
    font-size: 15px;
}
</style>
