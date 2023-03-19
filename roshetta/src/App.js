import "./styles/app.css";

export default function App() {
  return (
    <div className="App">
      <h1>تطبيق روشته</h1>
      <p>
        التطبيق بالعربي <br /> هتشتغلو بمكتبه <b>antd</b> حاجه زي البوتستراب كد
        بس فيها المكونات كلها <br /> هتشتغلو بخط اسمه <b>Cairo</b>
      </p>
      <ul>
        <li>
          <h2> محمد الحسن ابو الهيشام</h2>
          <ul>
            <li>صفحات تسجيل الدخول (pages/auth/)</li>
            <li>صفحات الادمن (pages/admin)</li>
            <li>صفحات الدكتور (pages/doctor)</li>
          </ul>
        </li>
        <li>
          <h2>محمد عثمان</h2>
          <ul>
            <li>صفحات المريض (pages/patient/)</li>
            <li>صفحات الصيدلي (pages/pharmcist)</li>
            <li>صفحات المساعد (pages/assistant)</li>
          </ul>
        </li>
      </ul>
    </div>
  );
}
