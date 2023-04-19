import React from 'react';
import './HomePage.scss';
import images from '../../images';
import { Link } from 'react-router-dom';
const HomePage = () => {
  return (
    <>
      <div className="home-page">
        <h1 className="home-page__heading">روشتة</h1>
        <p className="home-page__title">
          نرحب بكم في تطبيق روشته الذى يساعدك في ايجاد دكتور مناسب لك
        </p>
        <div className="home-page__img">
          <img
            className="home-page__img"
            src={images.homePageImage}
            alt="home page"
          />
        </div>
        <p className="home-page__login-text">قم باختيار نوع التسجيل الخاص بك</p>
        <div className="home-page__btn--container">
          <Link to={'/login'} className="home-page__btn--fill">
            تسجيل الدخول
          </Link>
          <Link to={'/register'} className="home-page__btn">
            انشاء حساب
          </Link>
        </div>
        <p className="home-page__footer">
          برمجه فريق <span>روشتة</span> 2023
        </p>
      </div>
    </>
  );
};

export default HomePage;
