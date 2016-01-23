package com.mjapps.wtc;

import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Spinner;
import android.widget.ArrayAdapter;
import android.view.View;
import android.widget.Toast;

import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.GoogleMap.OnMapClickListener;
import com.google.android.gms.maps.GoogleMapOptions;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.List;

public class MoveActivity extends FragmentActivity implements OnMapClickListener, AdapterView.OnItemSelectedListener {

    private GoogleMap mMap;
    private LatLng mPoint;
    private TextView mTapTextView;

    private Spinner spinner;
    private Marker lastMark;
    private Button button;

    private HttpResponse resp;
    private ProgressDialog mLoadingDialog;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_movethecar);

        mTapTextView = (TextView) findViewById(R.id.tap_text);
        spinner = (Spinner) findViewById(R.id.spinner);
        button = (Button) findViewById(R.id.button);

        setUpPageIfNeeded();
    }

    @Override
    protected void onResume() {
        super.onResume();
       // setUpPageIfNeeded();
    }

    private void setUpPageIfNeeded() {
        if (1==1) { // Check internet connection

            setUpMapIfNeeded();
            setUpRestOfPage();
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

        mMap.setOnMapClickListener(this);
    }

    private void setUpRestOfPage() {
        ArrayAdapter<CharSequence> adapter = ArrayAdapter.createFromResource(this,
                R.array.signs_array, android.R.layout.simple_spinner_item);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setOnItemSelectedListener(this);
        spinner.setAdapter(adapter);
    }


    @Override
    public void onMapClick(LatLng point) {
        if(lastMark!=null){lastMark.remove();}
        lastMark = mMap.addMarker(new MarkerOptions().position(point));
        mTapTextView.setText("tapped, point=" + point);
        mPoint = point;
        spinner.setClickable(true);
    }

    public void onItemSelected(AdapterView<?> parent, View view, int pos, long id) {
        if(spinner.getSelectedItem()!="Select..."&&mPoint!=null) {
            button.setEnabled(true);
        } else {button.setEnabled(false);}
    }

    public void onNothingSelected(AdapterView<?> parent) {
        button.setEnabled(false);
    }


    public void moveSubmit(View view) {
        String authKey,lat=null,lng=null,carKey=null,who=null,sign=null,verb=null;
        if((Double)mPoint.latitude!=null){lat = Double.toString(mPoint.latitude);}
        if((Double)mPoint.longitude!=null){lng = Double.toString(mPoint.longitude);}
        if(spinner.getSelectedItem()!=null&&spinner.getSelectedItem()!="Select..."){sign=(String)spinner.getSelectedItem();}
        who="A";
        carKey="1";
        authKey="AuthKeyMJAPPS10025";
        verb="move";

        if(lat!=null&&lng!=null&&sign!=null&&carKey!=null) {
            new MyAsyncTask().execute(authKey,verb,carKey,lat,lng,who,sign);
           // Toast.makeText(getApplicationContext(), "hi2", Toast.LENGTH_SHORT).show();
        }

    }

    private class MyAsyncTask extends AsyncTask<String, Integer, Double>{
        @Override
        protected Double doInBackground (String... params) {
            HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost("http://speechwithjulie.com/wtc/api.php");

         //   Toast.makeText(getApplicationContext(), "hi1", Toast.LENGTH_SHORT).show();
            try {
                List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(7);
                nameValuePairs.add(new BasicNameValuePair("authKey", params[0]));
                nameValuePairs.add(new BasicNameValuePair("verb", params[1]));
                nameValuePairs.add(new BasicNameValuePair("carKey", params[2]));
                nameValuePairs.add(new BasicNameValuePair("lat", params[3]));
                nameValuePairs.add(new BasicNameValuePair("lng", params[4]));
                nameValuePairs.add(new BasicNameValuePair("who", params[5]));
                nameValuePairs.add(new BasicNameValuePair("sign", params[6]));

                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));

                resp = httpclient.execute(httppost);

           //     Toast.makeText(getApplicationContext(),obj.getString("Status") , Toast.LENGTH_LONG).show();
           //     if(obj.getString("Status").equals("SUCCESS")) {
           //         Toast.makeText(getApplicationContext(), "Car move successfully recorded!", Toast.LENGTH_SHORT).show();
           //     }
                //JSONArray dropped = (JSONArray)data.get("dropped");
                //JSONObject first = (JSONObject)dropped.get(0);
                //System.out.println( first.get("crc32"));
            } catch (ClientProtocolException e) {
                Log.d("MJH","ClientExcept");
            } catch (IOException e) {
                Log.d("MJH","IOExcept");

            }
            return null;
        }


        protected void onPreExecute(){
            mLoadingDialog = ProgressDialog.show(MoveActivity.this, "Updating...", "Please wait...", true,false);
        }


        protected void onPostExecute(Double result){

            try {
                JSONObject jsonResp = new JSONObject(EntityUtils.toString(resp.getEntity()));
                if(jsonResp.getString("Status").equals("SUCCESS")) {
                    Toast.makeText(getApplicationContext(), "Car move successfully recorded!", Toast.LENGTH_SHORT).show();
                } else {
                }
            } catch (JSONException e) {
                Log.d("MJH","JSONExcept");
            } catch (IOException e) {
                Log.d("MJH","IOExcept");
            } finally {
                mLoadingDialog.dismiss();
            }
        }
    }
}
