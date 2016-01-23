package com.mjapps.wtc;

import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.GoogleMap.OnMapClickListener;
import com.google.android.gms.maps.GoogleMapOptions;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

public class WhereActivity extends FragmentActivity {

    private GoogleMap mMap;
    private LatLng mPoint;
    private TextView mTapTextView;
    private String lat;
    private String lng;

    private HttpResponse resp;
    private ProgressDialog mLoadingDialog;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_wheresthecar);

        mTapTextView = (TextView) findViewById(R.id.tap_text);

        setUpPageIfNeeded();
    }

    @Override
    protected void onResume() {
        super.onResume();
        setUpPageIfNeeded();
    }

    private void setUpPageIfNeeded() {
        if (1==1) { // Check internet connection

            mLoadingDialog = ProgressDialog.show(WhereActivity.this, "Finding your car...", "Please wait...", true,false);
            setUpVariables();
            setUpMapIfNeeded();
            mLoadingDialog.dismiss();
        }
    }

    private void setUpMapIfNeeded() {
        if (mMap == null) {
            mMap = ((SupportMapFragment) getSupportFragmentManager().findFragmentById(R.id.map))
                    .getMap();
            if (mMap != null) {
                setUpMap();
            }
        }
    }

    private void setUpMap() {
        GoogleMapOptions options = new GoogleMapOptions();
        options.compassEnabled(false)
                .rotateGesturesEnabled(false)
                .tiltGesturesEnabled(false);
    }


    public void setUpVariables() {
        String authKey,carKey=null,verb=null;
        carKey="1";
        authKey="AuthKeyMJAPPS10025";
        verb="retrieve";

        if(carKey!=null) {
            new MyAsyncTask().execute(authKey,verb,carKey);
        }

    }

    private class MyAsyncTask extends AsyncTask<String, Integer, Double>{

        @Override
        protected Double doInBackground (String... params) {
            HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost("http://speechwithjulie.com/wtc/api.php");

            try {
                List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(3);
                nameValuePairs.add(new BasicNameValuePair("authKey", params[0]));
                nameValuePairs.add(new BasicNameValuePair("verb", params[1]));
                nameValuePairs.add(new BasicNameValuePair("carKey", params[2]));

                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));

                resp = httpclient.execute(httppost);
//Log.d("MJH",EntityUtils.toString(resp.getEntity()));
            } catch (ClientProtocolException e) {
                Log.d("MJH","ClientExcept");
            } catch (IOException e) {
                Log.d("MJH","IOExcept");

            }
            return null;
        }

        @Override
        protected void onPreExecute(){
            super.onPreExecute();
        }

        @Override
        protected void onPostExecute(Double result){
            super.onPostExecute(result);
            try {
                JSONObject jsonResp = new JSONObject(EntityUtils.toString(resp.getEntity()));
                if(jsonResp.getString("Status").equals("SUCCESS")) {
                    lat=jsonResp.getString("lat");
                    lng=jsonResp.getString("lng");
                    mTapTextView.setText(lat+","+lng);
                } else {
                }
            } catch (JSONException e) {
                Log.d("MJH","JSONExcept");
            } catch (IOException e) {
                Log.d("MJH", "IOExcept");
            }
        }
    }
}
