<template>
    <b-container>
        <b-row>
            <b-col cols="6">
                <b-card class="mt-5">
                    <h5 class="mb-4">Modal</h5>
                    <div>
                        <b-button variant="success" v-b-modal.modal-1>Launch modal</b-button>

                        <b-modal
                            id="modal-1"
                            ok-title="Submit"
                            ok-variant="success"
                            cancel-variant="danger"
                            cancel-title="Reject"
                            centered
                            title="Modal Header">
                            <p class="my-4">Hello from modal!</p>
                        </b-modal>
                    </div>
                </b-card>
            </b-col>
            <b-col cols="6">
                <b-card class="mt-5">
                    <h5 class="mb-4">Modal Large</h5>
                    <div>
                        <b-button variant="success" v-b-modal.modal-2>Launch modal</b-button>

                        <b-modal
                            id="modal-2"
                            ok-title="Submit"
                            ok-variant="success"
                            cancel-variant="danger"
                            cancel-title="Reject"
                            size="xl"
                            centered
                            title="Modal Header">
                            <p class="my-4">Hello from modal!</p>
                        </b-modal>
                    </div>
                </b-card>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="6">
                <b-card class="mt-5">
                    <h5 class="mb-4">Modal Scrollable</h5>
                    <div>
                        <b-button variant="success" v-b-modal.modal-3>Launch modal</b-button>

                        <b-modal
                            id="modal-3"
                            ok-title="Submit"
                            ok-variant="success"
                            cancel-variant="danger"
                            cancel-title="Reject"
                            scrollable
                            centered
                            title="Modal Header">
                            <p class="my-4 pb-4" v-for="i in 10" :key="i">
                                <span class="font-weight-bold">What is Lorem Ipsum?</span> <br/>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                            </p>
                        </b-modal>
                    </div>
                </b-card>
            </b-col>
            <b-col cols="6">
                <b-card class="mt-5">
                    <h5 class="mb-4">Modal Withow Backdrop</h5>
                    <div>
                        <b-button variant="success" v-b-modal.modal-4>Launch modal</b-button>

                        <b-modal
                            id="modal-4"
                            ok-title="Submit"
                            ok-variant="success"
                            cancel-variant="danger"
                            cancel-title="Reject"
                            content-class="shadow"
                            hide-backdrop
                            centered
                            title="Modal Header">
                            <p class="my-4">Hello from modal!</p>
                        </b-modal>
                    </div>
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>
