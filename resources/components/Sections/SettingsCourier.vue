<script setup>
  import { computed } from 'vue'
  import SettingRow from '../UI/SettingRow.vue'

  const props = defineProps({
    settings: Object,
  })

  const settings = computed(() => props.settings)
  const from = computed(() => props.settings.from)
</script>

<template>
  <div class="k-courier-settings">
    <k-headline>{{ $t('beebmx.courier.panel.settings.courier') }}</k-headline>

    <table>
      <tbody>
        <SettingRow :field="$t('beebmx.courier.panel.courier.logo')" :value="settings?.logo || 'null'" />
        <SettingRow :field="$t('beebmx.courier.panel.courier.from.name')" :value="from?.name || 'null'" />
        <SettingRow :field="$t('beebmx.courier.panel.courier.from.email')" :value="from?.address || 'null'" />
      </tbody>
    </table>
  </div>
</template>

<style scoped>
  .k-courier-settings {
    margin-top: var(--spacing-8);
  }
  .k-courier-settings table {
    background: light-dark(var(--color-gray-100), var(--color-gray-850));
    border-radius: var(--rounded-lg);
    box-shadow: var(--shadow-sm);
    margin-top: var(--spacing-2);
    overflow: hidden;
  }

  @container (min-width: 30rem) {
    /**/
  }
  @media (min-width: 60rem) {
    /**/
  }
</style>
