class ApiUrls {
  // Root
  static const domain = "http://10.0.2.2:3030";
  static const _root = "$domain/roshetta/api";

  // Global Auth
  static const login = "$_root/users/login";
  static const register = "$_root/users/register";
  static const logout = "$_root/users/logout";
  static const verifyEmailCode = "$_root/users/active_email";
  static const forgotPassword = "$_root/users/forget_password";
  static const verifyForgotPasswordCode = "$_root/users/code_password";
  static const resetForgotPass = "$_root/users/reset_password";
  static const getDoctorSpecialists = "$_root/users/doctor_specialist";

  // Shared protected pages
  static const explainVideo = "$_root/users/view_video";
  static const myprofile = "$_root/users/profile";
  static const editProfile = "$_root/users/edit_profile";
  static const editPassword = "$_root/users/edit_password";
  static const addProfileImage = "$_root/users/add_image";

  // Patient
  static const patientClinics = "$_root/patients/view_clinic";
  static const patientAddAppointment = "$_root/patients/add_appointment";
  static const patientViewAppointments = "$_root/patients/view_appointment";
  static const patientEditAppointments = "$_root/patients/edit_appointment";
  static const patientDeleteAppointments = "$_root/patients/delete_appointment";
  static const patientClinicDetails = "$_root/patients/view_clinic_details";
  static const patientPrescripts = "$_root/patients/view_prescript";
  static const patientPrescriptDetails =
      "$_root/patients/view_prescript_details";
  static const patientDiseases = "$_root/patients/view_disease";
  static const patientPharmacy = "$_root/patients/view_pharmacy";
  static const patientPharmacyDetails = "$_root/patients/view_pharmacy_details";
  static const patientPharmacyOrder = "$_root/patients/send_prescript_pharmacy";
  static const patientViewOrders = "$_root/patients/view_orders";
  static const patientDeleteOrder = "$_root/patients/delete_order";

  // Docotr And Pharmacist
  static const verifyAccountImages = "$_root/users/active_image_person";
  static const verifyPlace = "$_root/users/active_image_place";
  static const viewAccountStatus = "$_root/users/view_account_status";

  // Doctor
  static const addClinic = "$_root/doctors/add_clinic";
  static const editClinic = "$_root/doctors/edit_clinic";
  static const addLogoClinic = "$_root/doctors/add_clinic_image";
  static const doctorViewClinics = "$_root/doctors/view_clinic";
  static const doctorLoginClinic = "$_root/doctors/login_clinic";
  static const logoutClinic = "$_root/doctors/logout_clinic";

  static const doctorAssistClinic = "$_root/doctors/view_assistant";
  static const doctorAddAssistClinic = "$_root/doctors/add_assistant";
  static const doctorDeleteAssistClinic = "$_root/doctors/delete_assistant";

  static const doctorAppointments = "$_root/doctors/view_appoint";
  static const doctorPatient = "$_root/doctors/view_patient_details";
  static const doctorAddPrescript = "$_root/doctors/add_patient_prescript";
  static const doctorDiseasePrescript = "$_root/doctors/view_disease_prescript";
  static const doctorPrescriptDetails =
      "$_root/doctors/view_disease_prescript_details";

  // Assistant
  static const assistantClinics = "$_root/assistants/view_clinic";
  static const assistantClinicLogin = "$_root/assistants/login_clinic";
  static const assistantClinicEdit = "$_root/assistants/edit_clinic";
  static const assistantClinicLogout = "$_root/assistants/logout_clinic";
  static const assistantAppoints = "$_root/assistants/view_appoint";
  static const assistantAppointModify =
      "$_root/assistants/modify_appoint_status";

  // Pharmacist
  static const addPharmacy = "$_root/pharmacists/add_pharmacy";
  static const editPharmacy = "$_root/pharmacists/edit_pharmacy";
  static const addLogoPharmacy = "$_root/pharmacists/add_pharmacy_image";
  static const pharmacyViewPharmacs = "$_root/pharmacists/view_pharmacy";
  static const doctorLoginPharmacy = "$_root/pharmacists/login_pharmacy";
  static const logoutPharmcay = "$_root/pharmacists/logout_pharmacy";
  static const pharmacyOrders = "$_root/pharmacists/view_order_pharmacy";
  static const pharmacyPrescript = "$_root/pharmacists/view_prescript";
  static const pharmacyConfirmPrescript =
      "$_root/pharmacists/confirm_pay_prescript";
  static const pharmacyViewPaid = "$_root/pharmacists/view_pay_prescript";
}
