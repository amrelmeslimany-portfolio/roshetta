import React, { useEffect, useState } from 'react';
import images from '../../../images';
import axios from 'axios';
import { GoVerified } from 'react-icons/go';
import { AppWrapper } from '../../../wrapper';
import { viewProfile } from '../API';
import {
  FaDemocrat,
  FaFileMedical,
  FaGasPump,
  FaLocationArrow,
  FaMailBulk,
  FaPhone,
} from 'react-icons/fa';
import { GiCityCar, GiDoctorFace, GiPhone } from 'react-icons/gi';
const PersonalData = () => {
  const [userData, setUserData] = useState({});

  const buttonMode = () => {};

  useEffect(() => {
    viewProfile().then((data) => {
      console.log(data.Data);
      setUserData(data.Data);
    });
  }, []);

  if (!userData) {
    return (
      <>
        <h2 className="flex h-screen items-center justify-center text-3xl">
          Loading...
        </h2>
      </>
    );
  }

  if (userData) {
    return (
      <>
        <div className=" bg-gray-100 p-2">
          <img
            // src={userData.image}
            src={images.doctorEditInfo}
            className="mx-auto block w-1/6 rounded-full"
            alt="doctor-info"
          />
          <div className="mx-auto flex items-center justify-center">
            <h2 className="text-2xl text-roshetta ">{userData.name}</h2>
            <GoVerified className="mx-2 text-blue-600" />
          </div>
          <div className="mx-auto text-center">
            <h2 className="m-auto text-xl text-slate-400 text-slate-500">
              {userData.ssd}
            </h2>
          </div>

          <div className="m-auto mt-2 flex w-1/2 items-center justify-evenly gap-20 rounded-2xl bg-white py-2 shadow-lg ">
            <div className="flex flex-col items-center justify-center gap-2">
              <p className="text-slate-400">الجنس</p>
              <p>{userData.gender}</p>
            </div>
            <div className="flex flex-col items-center justify-center">
              <p className="text-slate-400">تاريخ الميلاد</p>
              <p>{userData.birth_date}</p>
            </div>
            <div className="flex flex-col items-center justify-center">
              <p className="text-slate-400">نوع الحساب</p>
              <p>{userData.type}</p>
            </div>
          </div>
          <div className="mx-auto mt-4 flex w-1/2 flex-col items-start justify-start gap-3">
            <div className="flex items-start justify-start ">
              <FaFileMedical className="ml-8 text-xl text-slate-400" />{' '}
              <div className="flex flex-col items-start justify-start">
                <p className="text-slate-500">التخصص</p>
                <p className="font-bold text-slate-800">
                  {userData.specialist}
                </p>
              </div>
            </div>
            <div className="flex items-start justify-start">
              <FaMailBulk className="ml-8 text-xl text-slate-400" />{' '}
              <div className="flex flex-col items-start justify-start">
                <p className="text-slate-500">البريد الإلكتروني</p>
                <p className="font-bold text-slate-800">{userData.email}</p>
              </div>
            </div>
            <div className="flex items-start justify-start">
              <FaPhone className="ml-8 text-xl text-slate-400" />{' '}
              <div className="flex flex-col items-start justify-start">
                <p className="text-slate-500">رقم التليفون</p>
                <p className="font-bold text-slate-800">
                  {userData.phone_number}
                </p>
              </div>
            </div>
            <div className="flex items-start justify-start">
              <FaLocationArrow className="ml-8 text-xl text-slate-400" />{' '}
              <div className="flex flex-col items-start justify-start">
                <p className="text-slate-500">العنوان</p>
                <p className="font-bold text-slate-800">
                  {userData.governorate}
                </p>
              </div>
            </div>
          </div>
          <div className="mx-auto mt-4 flex w-1/2 items-center justify-around">
            <div className="flex flex-col items-start justify-start">
              <p className="text-slate-500">العيادات</p>
              <p className="font-bold text-slate-800">
                {userData.number_clinic}
              </p>
            </div>
            <div className="flex flex-col items-start justify-start border-x-2 border-slate-300 px-20">
              <p className="text-slate-500">الروشتات</p>
              <p className="font-bold text-slate-800">
                {userData.number_prescript}
              </p>
            </div>
            <div className="flex flex-col items-start justify-start">
              <p className="text-slate-500">المساعدين</p>
              <p className="font-bold text-slate-800">
                {userData.number_appoint}
              </p>
            </div>
          </div>
          <div className="mx-auto flex items-center justify-center">
            <button
              onClick={() => buttonMode('edit-info')}
              className="foucs:outline-2 mt-6 rounded-full bg-roshetta px-40 py-3 text-2xl text-white hover:bg-green-500 focus:outline-none focus:ring focus:ring-green-300  active:bg-green-600"
              type="button"
            >
              تعديل الحساب
            </button>
          </div>
        </div>
      </>
    );
  }
};

export default AppWrapper(PersonalData);
