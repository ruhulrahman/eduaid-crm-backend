<template>
    <b-container>
        <b-row>
            <b-col>
                <b-card class="mt-5">
                    <h5 class="mb-4">Buttons Solid</h5>
                    <b-button variant="primary">Primary</b-button>&nbsp;
                    <b-button variant="secondary">Secondary</b-button>&nbsp;
                    <b-button variant="success">Success</b-button>&nbsp;
                    <b-button variant="danger">Danger</b-button>&nbsp;
                    <b-button variant="warning">Warning</b-button>&nbsp;
                    <b-button variant="info">Info</b-button>&nbsp;
                    <b-button variant="light">Light</b-button>&nbsp;
                    <b-button variant="dark">Dark</b-button>
                </b-card>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <b-card class="mt-5">
                    <h5 class="mb-4">Buttons Ouline</h5>
                    <b-button variant="outline-primary">Primary</b-button>&nbsp;
                    <b-button variant="outline-secondary">Secondary</b-button>&nbsp;
                    <b-button variant="outline-success">Success</b-button>&nbsp;
                    <b-button variant="outline-danger">Danger</b-button>&nbsp;
                    <b-button variant="outline-warning">Warning</b-button>&nbsp;
                    <b-button variant="outline-info">Info</b-button>&nbsp;
                    <b-button variant="outline-light">Light</b-button>&nbsp;
                    <b-button variant="outline-dark">Dark</b-button>
                </b-card>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="6">
                <b-card class="mt-5">
                    <h5 class="mb-4">Buttons Sizing</h5>
                    <b-button variant="primary" size="lg">Large</b-button>&nbsp;
                    <b-button variant="primary">Regular</b-button>&nbsp;
                    <b-button variant="primary" size="sm">Small</b-button>&nbsp;
                </b-card>
            </b-col>
            <b-col cols="6">
                <b-card class="mt-5">
                    <h5 class="mb-4">Button Group</h5>
                    <b-button-group>
                        <b-button variant="primary">Button 1</b-button>
                        <b-button variant="primary">Button 2</b-button>
                        <b-button variant="primary">Button 3</b-button>
                    </b-button-group>
                </b-card>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="6">
                <b-card class="mt-5">
                    <h5 class="mb-4">Dropdown Button</h5>
                    <b-button-group>
                        <b-dropdown left text="Dropdown Menu">
                        <b-dropdown-item>Item 1</b-dropdown-item>
                        <b-dropdown-item>Item 2</b-dropdown-item>
                        <b-dropdown-item>Item 3</b-dropdown-item>
                        </b-dropdown>
                    </b-button-group>
                </b-card>
            </b-col>
        </b-row>
        <b-row class="mb-5"></b-row>
    </b-container>
</template>
