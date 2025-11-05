<script setup>
  import { computed } from 'vue'
  import SettingRow from '../UI/SettingRow.vue'

  const props = defineProps({
    transport: Object,
    plus: {
      type: String | null,
      default: null,
    },
  })

  const transport = computed(() => props.transport)
  const plus = computed(() => props.plus)
  const isSmtp = computed(() => transport.value?.type === 'smtp')
  const provider = computed(() => plus.value || transport.value?.type)
</script>

<template>
  <div class="k-courier-settings">
    <k-headline>{{ $t('beebmx.courier.panel.settings.provider') }}</k-headline>

    <table>
      <tbody>
        <SettingRow :field="$t('beebmx.courier.panel.email.provider')" :value="provider || 'null'" />
        <SettingRow field="Host" :value="isSmtp ? transport?.host || 'null' : $t('beebmx.courier.panel.email.na')" />
        <SettingRow field="Port" :value="isSmtp ? transport?.port || 'null' : $t('beebmx.courier.panel.email.na')" />
        <SettingRow field="Security" :value="isSmtp ? transport?.security || 'null' : $t('beebmx.courier.panel.email.na')" />
        <SettingRow field="Auth" :value="isSmtp ? transport?.auth || 'null' : $t('beebmx.courier.panel.email.na')" />
        <SettingRow field="Username" :value="isSmtp ? transport?.username || 'null' : $t('beebmx.courier.panel.email.na')" />
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
