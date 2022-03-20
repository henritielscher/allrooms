import "../css/main.scss";
import "../img/agency.png";
import "../img/allrooms.png";
import "../icons/favicon.png";
import "../icons/management.png";
import "../icons/marketing.png";
import "../icons/socialmedia.png";
import "../icons/tourbooking.png";
import Navbar from "./modules/Navbar";
import ContactForm from "./modules/ContactForm";
import Newsletter from "./modules/Newsletter";
import DataTable from "./modules/dataTable";
import Slider from "./modules/Slider";
import ArtistFilter from "./modules/ArtistsFilter";
import LanguageCookie from "./modules/LanguageCookie";
import LazyLoading from "./modules/LazyLoading";
import BerlinShows from "./modules/BerlinShows";

const languageCookie = new LanguageCookie();
const navbar = new Navbar();
const contactForm = new ContactForm();
const newsletter = new Newsletter();
const datatable = new DataTable();
const swiper = new Slider();
const lazyloader = new LazyLoading();
const artistFilter = new ArtistFilter();
const berlinShows = new BerlinShows();
