<template>
    <div v-if="visible" class="modal-overlay" @click="closeOnOverlayClick">
        <div class="modal-content" @click.stop>
            <button class="close-button" @click="close">&times;</button>
            <form action="" id="articles-form">
                <label for="title">Názov</label>
                <input type="text" id="title">
                <label for="author">Autor</label>
                <input type="text" id="author">
                <label for="date">Dátum</label>
                <input type="date" id="date">
                <input type="file" style="margin-top: 1rem; margin-bottom: 1rem;">
                <button id="btn-submit">Vložiť</button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "ArticlesForm",
    props: {
        visible: {
            type: Boolean,
            required: true,
        },
        closeOnOverlay: {
            type: Boolean,
            default: true,
        },
    },
    emits: ["update:visible"],
    methods: {
        close() {
            this.$emit("update:visible", false);
        },
        closeOnOverlayClick() {
            if (this.closeOnOverlay) {
                this.close();
            }
        },
    },
};
</script>

<style scoped>
#articles-form {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 0.2rem;
    font-size: 1.5rem;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    position: relative;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    font-size: 20px;
    cursor: pointer;
}

#btn-submit {
    padding: 5px;
    font-size: 2rem;
    background-color: #52b69a;
    color: #fefae0;
    border: 2px solid #52796f;
}
</style>