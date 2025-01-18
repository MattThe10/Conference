<template>
    <div class="modal-backdrop" v-if="user">
        <div class="modal">

            <div class="modal-header">
                <div class="modal-title">
                    Detaily
                </div>
                <button type="button" class="btn-close" @click="close" />
            </div>

            <div class="modal-body">

                <div class="data-group">
                    <div class="type">
                        Email
                    </div>
                    <div class="value">
                        {{ user.email }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Meno
                    </div>
                    <div class="value">
                        {{ user.name }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Priezvisko
                    </div>
                    <div class="value">
                        {{ user.surname }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Univerzita
                    </div>
                    <div class="value">
                        {{ user.faculty.university.name }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Fakulta
                    </div>
                    <div class="value">
                        {{ user.faculty.name }}
                    </div>
                </div>

                <div class="data-group">
                    <div class="type">
                        Rola
                    </div>
                    <div class="value">
                        {{ user.role.name }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: {
                type: Object,
                required: true,
            },
        },
        methods: {
            close() {
                this.$emit('close');
            },
        }
    };
</script>

<style>
    .modal-backdrop {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal {
        background: #FFFFFF;
        box-shadow: 2px 2px 20px 1px;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
        padding: 16px;
        border-radius: 12px;
        width: 30vw;
    }

    @media only screen and (max-width: 1200px) {
        .modal {
            width: 50vw;
        }
    }

    @media only screen and (max-width: 720px) {
        .modal {
            width: 80vw;
        }
    }

    @media only screen and (max-width: 440px) {
        .modal {
            width: 100vw;
            height: 100vh;
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        font-size: 2rem;
    }

    .modal-header .modal-title {
        display: flex;
        align-items: center;
    }

    .modal-header .btn-close {
        width: 64px;
        height: 64px;
        font-size: 2rem;
        background-color: transparent;
        border: none;
    }

    .modal-header .btn-close::before {
        content: "\2715";
        font-weight: 700;
    }

    .modal-body {
        padding: 16px 0;
    }

    .modal-footer .btn-submit {
        width: 100%;
        height: 64px;
        background-color: #52b69a;
        border: none;
        border-radius: 12px;
        color: #fefae0;
        font-size: 1.2rem;
    }

    .data-group {
        display: flex;
        flex-direction: column;
        text-align: left;
        gap: 8px;
        margin-bottom: 16px;
    }

    .data-group .type {
        font-size: 1.2rem;
        font-weight: 700;
    }

    .data-group .value {
        height: 40px;
        font-size: 1.2rem;
        padding: 8px;
    }
</style>
