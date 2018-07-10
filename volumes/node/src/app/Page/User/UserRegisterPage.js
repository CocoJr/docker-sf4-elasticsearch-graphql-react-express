/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

import 'isomorphic-fetch';
import React from 'react';
import Grid from "@material-ui/core/es/Grid/Grid";
import Typography from "@material-ui/core/es/Typography/Typography";
import registerMutation from "../../Gql/User/register";
import {findDOMNode} from "react-dom";
import InputField from "../../Component/Core/Form/InputField";
import GlobalFormErrors from "../../Component/Core/Form/GlobalFormErrors";
import {URL_LOGIN} from "../../routes";
import SubmitBtn from "../../Component/Core/Form/SubmitBtn";
import Mutation from "react-apollo/Mutation";
import {withApollo} from "react-apollo/index";
import styles from "../../Theme/form.css";
import withStyles from '@material-ui/core/es/styles/withStyles';
import {Link} from "react-router-dom";
import App from "../../Component/Core/app";
import {translate} from "react-i18next";
import {Helmet} from "react-helmet";

class UserRegisterPage extends React.Component
{
    render() {
        const {t, classes, i18n} = this.props;

        let username,
            email,
            password,
            passwordConfirm,
            termsAccepted = null;

        return (
            <Grid container justify={"center"} spacing={16}>
                <Helmet>
                    <title>Register</title>
                    <meta name="description" content="Register" />
                </Helmet>
                <Grid item xs={12}>
                    <Typography variant="headline" gutterBottom align="center">
                        {t('title')}
                    </Typography>
                </Grid>
                <Grid item xs={10} md={8}>
                    <Mutation mutation={registerMutation}
                              onCompleted={function(store) {
                                  this.register(store);
                              }.bind(this)}
                    >
                        {(userRegister, { loading, error, data }) => {
                            if (error) return App.renderErrors({error});

                                let formErrors = data && data.userRegister && data.userRegister.errors.length
                                ? data.userRegister.errors
                                : null;

                            return (
                                <form
                                    onSubmit={e => {
                                        e.preventDefault();

                                        userRegister({
                                            variables: {
                                                username: username.value,
                                                email: email.value,
                                                password: password.value,
                                                passwordConfirm: passwordConfirm.value,
                                                termsAccepted: termsAccepted.checked,
                                            }
                                        });
                                    }}
                                >
                                    {formErrors &&
                                    <Grid item className={classes.item}>
                                        <GlobalFormErrors errors={formErrors} t={t}/>
                                    </Grid>
                                    }

                                    <Grid item className={classes.item}>
                                        <InputField
                                            name={"username"}
                                            id={"username"}
                                            type={"text"}
                                            label={t('username')}
                                            errors={formErrors}
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
                                            name={"email"}
                                            id={"email"}
                                            type={"text"}
                                            label={t('email')}
                                            errors={formErrors}
                                            fullWidth={true}
                                            ref={node => {
                                                if (node) {
                                                    node = findDOMNode(node).getElementsByTagName('input')[0];
                                                }

                                                email = node;
                                            }}
                                        />
                                    </Grid>

                                    <Grid item className={classes.item}>
                                        <InputField
                                            name={"plainPassword"}
                                            id={"plainPassword"}
                                            type={"password"}
                                            label={t('password')}
                                            errors={formErrors}
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
                                        <InputField
                                            name={"passwordConfirm"}
                                            id={"passwordConfirm"}
                                            type={"password"}
                                            label={t('passwordConfirm')}
                                            errors={formErrors}
                                            fullWidth={true}
                                            ref={node => {
                                                if (node) {
                                                    node = findDOMNode(node).getElementsByTagName('input')[0];
                                                }

                                                passwordConfirm = node;
                                            }}
                                        />
                                    </Grid>

                                    <Grid item className={classes.item}>
                                        <InputField
                                            name={"termsAccepted"}
                                            id={"termsAccepted"}
                                            type={"checkbox"}
                                            label={t('termsAccepted')}
                                            errors={formErrors}
                                            ref={node => {
                                                if (node) {
                                                    node = findDOMNode(node).getElementsByTagName('input')[0];
                                                }

                                                termsAccepted = node;
                                            }}
                                        />
                                    </Grid>

                                    <Grid item className={classes.item}>
                                        <SubmitBtn isFetching={loading} isValid={true} text={t("submit")}/>
                                        &nbsp;
                                        <Link to={'/' + i18n.language + URL_LOGIN}>{t('login')}</Link>
                                    </Grid>
                                </form>
                            );
                        }}
                    </Mutation>
                </Grid>
            </Grid>
        );
    }

    register(store) {
        if (!store || !store.userRegister || !store.userRegister.errors || store.userRegister.errors.length) {
            return;
        }

        this.props.history.push(URL_LOGIN);
    }
}

export default translate('register')
(
    withApollo(
        withStyles(styles, {withTheme: true})
        (
            UserRegisterPage
        )
    )
);