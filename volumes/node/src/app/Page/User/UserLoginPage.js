/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

import React from 'react';
import {findDOMNode} from "react-dom";
import {withApollo} from "react-apollo/index";
import styles from '../../Theme/form.css';
import withStyles from '@material-ui/core/es/styles/withStyles';
import Grid from "@material-ui/core/es/Grid/Grid";
import Typography from "@material-ui/core/es/Typography/Typography";
import InputField from "../../Component/Core/Form/InputField";
import SubmitBtn from "../../Component/Core/Form/SubmitBtn";
import GlobalFormErrors from "../../Component/Core/Form/GlobalFormErrors";

import Mutation from "react-apollo/Mutation";
import loginMutation from "../../Gql/User/login";
import {setAuthToken} from "../../Util/core";
import {URL_REGISTER, URL_PROFIL} from "../../routes";
import {Link} from "react-router-dom";
import App from "../../Component/Core/app";
import { translate } from 'react-i18next';

class UserLoginPage extends React.Component
{
    render() {
        const {classes, t, i18n} = this.props;

        let username, password;

        return (
            <Grid container justify={"center"} spacing={16}>
                <Grid item xs={12}>
                    <Typography variant="headline" gutterBottom align="center">
                        {t('title')}
                    </Typography>
                </Grid>
                <Grid item xs={10} md={8}>
                    <Mutation mutation={loginMutation}
                              onCompleted={function(store) {
                                  this.login(store);
                              }.bind(this)}
                    >
                        {(userLogin, { loading, error, data }) => {
                            if (error) return App.renderErrors({error});

                            return (
                                <form
                                    onSubmit={e => {
                                        e.preventDefault();

                                        userLogin({
                                            variables: {
                                                username: username.value,
                                                password: password.value,
                                            },
                                        });
                                    }}
                                >
                                    {!loading && data &&
                                    <Grid item className={classes.item}>
                                        <GlobalFormErrors errors={data.userLogin.errors} t={t}/>
                                    </Grid>
                                    }

                                    <Grid item className={classes.item}>
                                        <InputField
                                            name={"username"}
                                            id={"username"}
                                            type={"text"}
                                            label={t('username')}
                                            fullWidth={true}
                                            ref={node => {
                                                if (node) {
                                                    node = findDOMNode(node).getElementsByTagName('input')[0];
                                                }

                                                username = node;
                                            }}
                                        />
                                    </Grid>

                                    <Grid item className={classes.item}>
                                        <InputField
                                            name={"plainPassword"}
                                            id={"plainPassword"}
                                            type={"password"}
                                            label={t('password')}
                                            fullWidth={true}
                                            ref={node => {
                                                if (node) {
                                                    node = findDOMNode(node).getElementsByTagName('input')[0];
                                                }

                                                password = node;
                                            }}
                                        />
                                    </Grid>

                                    <Grid item className={classes.item}>
                                        <SubmitBtn isFetching={loading} isValid={true} text={t('submit')}/>
                                        &nbsp;
                                        <Link to={'/' + i18n.language + URL_REGISTER}>{t('create_account')}</Link>
                                    </Grid>
                                </form>
                            )
                        }}
                    </Mutation>
                </Grid>
            </Grid>
        );
    }

    login(store) {
        if (!store || !store.userLogin || !store.userLogin.errors || store.userLogin.errors.length) {
            console.error('Impossible de vous connecter.');

            return;
        }

        const {i18n, client, history} = this.props;

        let token = store.userLogin.token;

        setAuthToken(token);

        client.onResetStore(function() {
            history.push('/' + i18n.language + URL_PROFIL);
        });

        client.resetStore();
    }
}

export default translate('login')
(
    withApollo(
        withStyles(styles)
        (
            UserLoginPage
        )
    )
);