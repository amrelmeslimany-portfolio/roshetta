class AppRoutes {
  // [ ] Temporary
  static const String tempAPIChanger = "/temp_api_changer";

  // Auth
  static const String intro = "/intro";
  static const String login = "/login";
  static const String createAccount = "/create_account";
  static const String verifyEmailCode = "/verify_email_code";
  static const String forgotPassword = "/forgot_password";
  static const String verifyForgotPassCode = "/verify_forgotpass_code";
  static const String resetForgotPass = "/reset_forgot_pass";
  // Pages
  static const String home = "/home";
  static const String myProfile = "/my_profile";
  static const String editProfile = "/edit_profile";
  static const String editPassword = "/edit_password";
  static const String settings = "/settings";
  static const String appointments = "/appointments";
  static const String readPrescript = "/readprescript";

  // Doctor & Pharmcay
  static const String verifyDPAccount = "/verify_docotor_pharmacy_account";

  // Patient
  static const String patientClinics = "/patient/clinics";
  static const String patientClinicDetails = "/patient/details";
  static const String patientappointments = "/patient/appointments";
  static const String patientPrescripts = "/patient/prescripts";
  static const String patientPrescriptDetails = "/patient/prescript/details";
  static const String patientDiseases = "/patient/diseases";
  static const String patientPharmacys = "/patient/pharmacys";
  static const String patientPharmacyDetails = "/patient/pharmacy/details";
  static const String patientOrders = "/patient/orders";

  // Doctor
  static const String doctorClinics = "/doctor/clinics";
  static const String doctorAddClinic = "/doctor/clinics/add";
  static const String doctorClinicDetails = "/doctor/clinics/details";
  static const String doctorClinicAssistant = "/doctor/clinics/assistant";
  static const String doctorAppointments = "/doctor/clinics/appointments";
  static const String doctorPatient = "/doctor/pateint";
  static const String doctorDiseasePrescripts = "/doctor/patient/prescripts";
  static const String doctorAddPrescript = "/doctor/prescript/add";
  static const String doctorChat = "/doctor/chat";

  // Assistant
  static const String assistantClinics = "/assistant/clinics";
  static const String assistantClinicEdit = "/assistant/clinics/edit";
  static const String assistantClinicDetails = "/assistant/clinics/details";
  static const String assistantAppointments = "/assistant/clinics/appointments";

  // Pharmacist
  static const String pharmacistPharmacys = "/pharmacist/pharmacys";
  static const String pharmacistPharmacyDetails =
      "/pharmacist/pharmacys/details";
  static const String addPharmacy = "/pharmacist/pharmacy/add";
  static const String pharmacyPrescripts = "/pharmacist/pharmacy/prescripts";
  static const String pharmacySellPrescript = "/pharmacist/prescripts/sell";
}

class AssetPaths {
  // Roots
  static const String _assetsPathIMGS = "assets/imgs";
  static const String _assetsPathVideos = "assets/videos";
  static const String _assetsPathLottie = "assets/lottie";
  static const String _assetsPathJSON = "assets/json";
  // Global
  static const String loading = '$_assetsPathLottie/loading.json';
  static const String empty = '$_assetsPathLottie/empty.json';
  static const String server = '$_assetsPathLottie/server.json';
  static const String offline = '$_assetsPathLottie/offline.json';
  static const String error = '$_assetsPathLottie/error.json';
  static const String waitng = '$_assetsPathLottie/waiting.json';
  static const String success = '$_assetsPathLottie/success.json';
  static const String appbar = '$_assetsPathIMGS/appbar-cover.jpg';
  static const String drawer = '$_assetsPathIMGS/drawer-cover.jpg';
  static const String emptyPerson =
      "https://media.istockphoto.com/id/1016744004/vector/profile-placeholder-image-gray-silhouette-no-photo.jpg?s=612x612&w=0&k=20&c=mB6A9idhtEtsFXphs1WVwW_iPBt37S2kJp6VpPhFeoA=";
  static const String emptyIMG =
      "https://www.namepros.com/attachments/empty-png.89209/";

  // Auth
  // static const String introAuth = '$_assetsPathIMGS/intro_auth.svg';
  static const String introAuth = '$_assetsPathIMGS/intro.png';
  // Logo
  static const String logoIcon = '$_assetsPathIMGS/lg-icon.png';

  // Home
  static const String usageVideo = '$_assetsPathVideos/roshetta.mp4';

  // Files
  static const String governmentsJson = '$_assetsPathJSON/governments.json';
}
