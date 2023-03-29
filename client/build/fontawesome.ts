import { config, library as FontawesomeLibrary } from '@fortawesome/fontawesome-svg-core'
import { faCircleCheck, faCircleXmark, faEye, faEyeSlash, faPencil, faPlus, faRight, faTrash } from '@fortawesome/pro-light-svg-icons'
import { faChevronDoubleLeft, faChevronLeft, faChevronRight } from '@fortawesome/pro-regular-svg-icons'
import '@fortawesome/fontawesome-svg-core/styles.css'

config.autoAddCss = false
FontawesomeLibrary.add(faPlus, faPencil, faTrash, faCircleCheck, faCircleXmark, faEye, faEyeSlash, faRight, faChevronDoubleLeft, faChevronLeft, faChevronRight)
