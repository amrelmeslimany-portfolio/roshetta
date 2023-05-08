import React, { useState } from 'react';
import {
  BarLoader,
  ClipLoader,
  RingLoader,
  RiseLoader,
  RotateLoader,
  SyncLoader,
} from 'react-spinners';

const override = {
  display: 'block',
  margin: '0 auto',
  borderColor: '#49ce91',
};

const MyLoader = ({ loading, text }) => {
  // const [loading, setLoading] = useState(false);
  const [color, setColor] = useState('#49ce91');

  return (
    <>
      <div className="flex h-screen flex-col items-center justify-center gap-5">
        <SyncLoader // RingLoader
          color={color}
          loading={loading}
          cssOverride={override}
          size={40}
          aria-label="Loading Spinner"
          data-testid="loader"
        />
        <h3 className="text-2xl font-bold text-roshetta">
          {text || 'جاري التحميل...'}
        </h3>
      </div>
    </>
  );
};

export default MyLoader;
