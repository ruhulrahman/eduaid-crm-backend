<template>
    <b-container>
        <b-row>
            <b-col>
                <div class="mt-5">
                    <b-card
                        title="Card Title"
                        img-src="https://picsum.photos/600/300/?image=25"
                        img-alt="Image"
                        img-top
                        class="mb-2"
                    >
                        <b-card-text class="mb-3">
                            Some quick example text to build on the card title and make up the bulk of the card's content.
                        </b-card-text>
                        <b-button href="#" variant="primary">Go somewhere</b-button>
                    </b-card>
                </div>
            </b-col>
            <b-col>
                <div class="mt-5">
                    <b-card title="Card title" sub-title="Card subtitle">
                        <b-card-text class="mb-3">
                            Some quick example text to build on the <em>card title</em> and make up the bulk of the card's content.
                        </b-card-text>
                        <b-card-text class="mb-3">A second paragraph of text in the card.</b-card-text>
                        <a href="#" class="card-link">Card link</a>
                        <b-link href="#" class="card-link">Another link</b-link>
                    </b-card>
                </div>
            </b-col>
            <b-col>
                <div class="mt-5">
                    <b-card class="text-center">
                        <b-card-text>
                            Some quick example text to build on the <em>card title</em> and make up the bulk of the card's content.
                        </b-card-text>
                    </b-card>
                </div>
            </b-col>
        </b-row>
        <b-row>
            <b-col sm="6">
                <div class="mt-4 mb-5">
                    <b-card no-body class="overflow-hidden">
                        <b-row no-gutters>
                            <b-col md="6">
                                <b-card-img src="https://picsum.photos/400/400/?image=20" alt="Image" class="rounded-0"></b-card-img>
                            </b-col>
                            <b-col md="6">
                                <b-card-body title="Horizontal Card">
                                <b-card-text>
                                    This is a wider card with supporting text as a natural lead-in to additional content.
                                    This content is a little bit longer.
                                </b-card-text>
                                </b-card-body>
                            </b-col>
                        </b-row>
                    </b-card>
                </div>
            </b-col>
        </b-row>
    </b-container>
</template>
