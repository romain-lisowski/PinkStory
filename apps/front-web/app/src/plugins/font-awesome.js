import { library } from '@fortawesome/fontawesome-svg-core'
import {
  faTimes,
  faBars,
  faVenusMars,
  faHeart,
  faChevronLeft,
  faChevronRight,
  faBold,
  faItalic,
  faUnderline,
  faQuoteRight,
  faHeading,
} from '@fortawesome/free-solid-svg-icons'
import FontAwesomeIcon from '@/libs/FontAwesomeIcon.vue'

library.add(faTimes)
library.add(faBars)
library.add(faVenusMars)
library.add(faHeart)
library.add(faChevronLeft)
library.add(faChevronRight)
library.add(faBold)
library.add(faItalic)
library.add(faUnderline)
library.add(faQuoteRight)
library.add(faHeading)

// eslint-disable-next-line import/prefer-default-export
export { FontAwesomeIcon }
