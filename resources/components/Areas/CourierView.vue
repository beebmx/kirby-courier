<script setup>
  import EmailSender from '../Sections/EmailSender.vue'
  import SettingsCourier from '../Sections/SettingsCourier.vue'
  import SettingsProvider from '../Sections/SettingsProvider.vue'

  const props = defineProps({
    courier: Object,
    transport: Object,
    user: Object,
    plus: {
      type: String | null,
      default: null,
    },
  })
</script>

<template>
  <k-panel-inside>
    <k-header>{{ $t('email') }}</k-header>

    <EmailSender :user="props.user" />
    <SettingsCourier :settings="props.courier" />
    <SettingsProvider :transport="props.transport" :plus="props.plus" />
  </k-panel-inside>
</template>

<style scoped>
  /**/
</style>
