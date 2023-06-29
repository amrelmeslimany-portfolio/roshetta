class ApiUrls {
  // Root
  // BUG
  // String domain = dotenv.env["CLIENT_API"]!; // "http://10.0.2.2:3030"
  // final String _root = "$domain/roshetta/api";
  static const clientAPI = "CLIENT_API";

  // Global Auth
  static const login = "/users/login";
  static const register = "/users/register";
  static const logout = "/users/logout";
  static const verifyEmailCode = "/users/active_email";
  static const forgotPassword = "/users/forget_password";
  static const verifyForgotPasswordCode = "/users/code_password";
  static const resetForgotPass = "/users/reset_password";
  static const getDoctorSpecialists = "/users/doctor_specialist";

  // Shared protected pages
  static const explainVideo = "/users/view_video";
  static const myprofile = "/users/profile";
  static const editProfile = "/users/edit_profile";
  static const editPassword = "/users/edit_password";
  static const addProfileImage = "/users/add_image";

  // Patient
  static const patientClinics = "/patients/view_clinic";
  static const patientAddAppointment = "/patients/add_appointment";
  static const patientViewAppointments = "/patients/view_appointment";
  static const patientEditAppointments = "/patients/edit_appointment";
  static const patientDeleteAppointments = "/patients/delete_appointment";
  static const patientClinicDetails = "/patients/view_clinic_details";
  static const patientPrescripts = "/patients/view_prescript";
  static const patientPrescriptDetails = "/patients/view_prescript_details";
  static const patientDiseases = "/patients/view_disease";
  static const patientPharmacy = "/patients/view_pharmacy";
  static const patientPharmacyDetails = "/patients/view_pharmacy_details";
  static const patientPharmacyOrder = "/patients/send_prescript_pharmacy";
  static const patientViewOrders = "/patients/view_orders";
  static const patientDeleteOrder = "/patients/delete_order";

  // Docotr And Pharmacist
  static const verifyAccountImages = "/users/active_image_person";
  static const verifyPlace = "/users/active_image_place";
  static const viewAccountStatus = "/users/view_account_status";

  // Doctor
  static const addClinic = "/doctors/add_clinic";
  static const editClinic = "/doctors/edit_clinic";
  static const addLogoClinic = "/doctors/add_clinic_image";
  static const doctorViewClinics = "/doctors/view_clinic";
  static const doctorLoginClinic = "/doctors/login_clinic";
  static const logoutClinic = "/doctors/logout_clinic";

  static const doctorAssistClinic = "/doctors/view_assistant";
  static const doctorAddAssistClinic = "/doctors/add_assistant";
  static const doctorDeleteAssistClinic = "/doctors/delete_assistant";

  static const doctorAppointments = "/doctors/view_appoint";
  static const doctorPatient = "/doctors/view_patient_details";
  static const doctorAddPrescript = "/doctors/add_patient_prescript";
  static const doctorDiseasePrescript = "/doctors/view_disease_prescript";
  static const doctorPrescriptDetails =
      "/doctors/view_disease_prescript_details";
  static const doctorChat = "/doctors/chat";

  // Assistant
  static const assistantClinics = "/assistants/view_clinic";
  static const assistantClinicLogin = "/assistants/login_clinic";
  static const assistantClinicEdit = "/assistants/edit_clinic";
  static const assistantClinicLogout = "/assistants/logout_clinic";
  static const assistantAppoints = "/assistants/view_appoint";
  static const assistantAppointModify = "/assistants/modify_appoint_status";

  // Pharmacist
  static const addPharmacy = "/pharmacists/add_pharmacy";
  static const editPharmacy = "/pharmacists/edit_pharmacy";
  static const addLogoPharmacy = "/pharmacists/add_pharmacy_image";
  static const pharmacyViewPharmacs = "/pharmacists/view_pharmacy";
  static const doctorLoginPharmacy = "/pharmacists/login_pharmacy";
  static const logoutPharmcay = "/pharmacists/logout_pharmacy";
  static const pharmacyOrders = "/pharmacists/view_order_pharmacy";
  static const pharmacyPrescript = "/pharmacists/view_prescript";
  static const pharmacyConfirmPrescript = "/pharmacists/confirm_pay_prescript";
  static const pharmacyViewPaid = "/pharmacists/view_pay_prescript";
}
