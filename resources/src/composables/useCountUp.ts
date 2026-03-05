import { ref, watch, type Ref } from 'vue'

/**
 * Animates a number from 0 to a target value over a duration using
 * requestAnimationFrame with ease-out easing.
 */
export function useCountUp(target: Ref<number>, duration = 2000) {
  const current = ref(0)

  watch(
    target,
    (newTarget) => {
      if (newTarget <= 0) {
        current.value = 0
        return
      }

      const start = performance.now()

      function step(now: number) {
        const elapsed = now - start
        const progress = Math.min(elapsed / duration, 1)
        // ease-out cubic
        const eased = 1 - Math.pow(1 - progress, 3)
        current.value = Math.round(eased * newTarget)

        if (progress < 1) {
          requestAnimationFrame(step)
        }
      }

      requestAnimationFrame(step)
    },
    { immediate: true },
  )

  return current
}
