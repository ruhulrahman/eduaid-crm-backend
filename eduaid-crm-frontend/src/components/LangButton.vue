<template>
    <div class="lang-switch-wrapper">
        <input id="language-toggle" class="check-toggle check-toggle-round-flat" type="checkbox" @click="changeLang()">
        <label for="language-toggle"></label>
        <span class="on">ENG</span>
        <span class="off">বাংলা</span>
    </div>
</template>

<script>
export default {
  methods: {
    changeLang () {
      this.$i18n.locale = this.$i18n.locale === 'bn' ? 'en' : 'bn'
    }
  }
}
</script>
