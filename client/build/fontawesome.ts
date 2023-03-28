import { config, library as FontawesomeLibrary } from '@fortawesome/fontawesome-svg-core'
import { faChevronLeft, faPencil, faPlus, faTrash } from '@fortawesome/pro-light-svg-icons'
import '@fortawesome/fontawesome-svg-core/styles.css'

config.autoAddCss = false
FontawesomeLibrary.add(faPlus, faPencil, faTrash, faChevronLeft)
