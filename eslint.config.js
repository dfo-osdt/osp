import antfu from '@antfu/eslint-config'
import quasar from 'eslint-plugin-quasar'

export default antfu(
  {
    vue: true,
    typescript: true,
  },
  {
    plugins: [quasar],
  },
)
