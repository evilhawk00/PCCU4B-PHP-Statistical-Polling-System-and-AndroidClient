package com.evilhawk00.pccu4b.ui;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.view.View;

import com.flyco.tablayout.CommonTabLayout;
import com.flyco.tablayout.listener.CustomTabEntity;
import com.flyco.tablayout.listener.OnTabSelectListener;
import com.evilhawk00.pccu4b.R;
import com.evilhawk00.pccu4b.entity.TabEntity;
import com.evilhawk00.pccu4b.utils.ViewFindUtils;

import java.util.ArrayList;
import java.util.Random;

public class MainActivity extends AppCompatActivity {
    private Context mContext = this;
    private ArrayList<Fragment> mFragments = new ArrayList<>();

    private String[] mTitles = {"Index", "Record", "Overview", "Members","Settings"};
    private int[] mIconUnselectIds = {
            R.mipmap.tab_home_unselect, R.mipmap.tab_enrolment_unselect,
            R.mipmap.tab_overview_unselect, R.mipmap.tab_members_unselect, R.mipmap.tab_more_unselect};
    private int[] mIconSelectIds = {
            R.mipmap.tab_home_select, R.mipmap.tab_enrolment_select,
            R.mipmap.tab_overview_select, R.mipmap.tab_members_select, R.mipmap.tab_more_select};
    private ArrayList<CustomTabEntity> mTabEntities = new ArrayList<>();
    private View mDecorView;
    private ViewPager mViewPager;

    private CommonTabLayout mTabLayout_2;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);


        mFragments.add(SimpleCardFragment.getInstance(mTitles[0]));
        mFragments.add(frag_enrolment_json.getInstance(mTitles[1]));
        mFragments.add(frag_overview_json.getInstance(mTitles[2]));
        mFragments.add(frag_members_json.getInstance(mTitles[3]));
        mFragments.add(frag_more_settings.getInstance(mTitles[4]));


        for (int i = 0; i < mTitles.length; i++) {
            mTabEntities.add(new TabEntity(mTitles[i], mIconSelectIds[i], mIconUnselectIds[i]));
        }

        mDecorView = getWindow().getDecorView();
        mViewPager = ViewFindUtils.find(mDecorView, R.id.vp_2);
        mViewPager.setAdapter(new MyPagerAdapter(getSupportFragmentManager()));

        mTabLayout_2 = ViewFindUtils.find(mDecorView, R.id.tl_2);

        tl_2();

    }

    Random mRandom = new Random();

    private void tl_2() {
        mTabLayout_2.setTabData(mTabEntities);
        mTabLayout_2.setOnTabSelectListener(new OnTabSelectListener() {
            @Override
            public void onTabSelect(int position) {
                mViewPager.setCurrentItem(position);
            }

            @Override
            public void onTabReselect(int position) {
                if (position == 0) {
                    mTabLayout_2.showMsg(0, mRandom.nextInt(100) + 1);

                }
            }
        });

        mViewPager.addOnPageChangeListener(new ViewPager.OnPageChangeListener() {
            @Override
            public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {

            }

            @Override
            public void onPageSelected(int position) {
                mTabLayout_2.setCurrentTab(position);
            }

            @Override
            public void onPageScrollStateChanged(int state) {

            }
        });

        mViewPager.setCurrentItem(1);
    }

    private class MyPagerAdapter extends FragmentPagerAdapter {
        public MyPagerAdapter(FragmentManager fm) {
            super(fm);
        }

        @Override
        public int getCount() {
            return mFragments.size();
        }

        @Override
        public CharSequence getPageTitle(int position) {
            return mTitles[position];
        }

        @Override
        public Fragment getItem(int position) {
            return mFragments.get(position);
        }
    }





    @Override
    public void onBackPressed() {
            AlertDialog.Builder builder = new AlertDialog.Builder(this);
            builder.setTitle(this.getResources().getString(R.string.Dialog_Label_ExitHeader));
            builder.setMessage(this.getResources().getString(R.string.Dialog_Label_ExitContent));

            builder.setPositiveButton(this.getResources().getString(R.string.Dialog_Label_Yes), new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int which) {
                    dialog.dismiss();
                    finish();
                }
            });

            builder.setNegativeButton(this.getResources().getString(R.string.Dialog_Label_Cancel), new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    dialog.dismiss();
                }
            });
            AlertDialog alert = builder.create();
            alert.show();

    }



}
