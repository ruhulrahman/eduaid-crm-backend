<template>
    <b-container>
        <b-row>
            <b-col>
                <b-card class="mt-5">
                    <h5 class="mb-4">Tooltip</h5>
                    <div class="d-flex align-center justify-content-around my-3">
                        <div class="mb-4">
                            <mark> Tooltip Left align </mark>&nbsp;
                            <b-button id="tooltip-target-2">
                                Hover Me
                            </b-button>
                            <b-tooltip target="tooltip-target-2" triggers="hover" placement="left">
                                I am tooltip <b>component</b> content!
                            </b-tooltip>
                        </div>
                        <div class="mb-4">
                            <mark> Tooltip Top align defult </mark>&nbsp;
                            <b-button variant="primary" v-b-tooltip.hover title="Tooltip directive content">
                                Hover Me
                            </b-button>
                        </div>
                        <div class="mb-4">
                            <mark> Tooltip Bottom align </mark>&nbsp;
                            <b-button id="tooltip-target-1">
                                Hover Me
                            </b-button>
                            <b-tooltip target="tooltip-target-1" triggers="hover" placement="bottom">
                                I am tooltip <b>component</b> content!
                            </b-tooltip>
                        </div>
                        <div class="mb-4">
                            <mark> Tooltip Right align </mark>&nbsp;
                            <b-button variant="primary" id="tooltip-target-3">
                                Hover Me
                            </b-button>
                            <b-tooltip target="tooltip-target-3" triggers="hover" placement="right">
                                I am tooltip <b>component</b> content!
                            </b-tooltip>
                        </div>
                    </div>
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>
