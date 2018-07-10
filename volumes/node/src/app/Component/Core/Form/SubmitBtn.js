/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

import Button from "@material-ui/core/es/Button/Button";
import React from "react";
import CircularProgress from "@material-ui/core/es/CircularProgress/CircularProgress";
import withStyles from '@material-ui/core/es/styles/withStyles';
import green from "@material-ui/core/es/colors/green";

class SubmitBtn extends React.Component {
    render() {
        return (
            <Button variant="raised"
                    color="primary"
                    type="submit"
                    disabled={this.props.isFetching || !this.props.isValid}
            >
                {this.props.text}
                {this.props.isFetching && this.props.isValid &&
                <CircularProgress size={24} className={this.props.classes.buttonProgress}/>
                }
            </Button>
        );
    }
}

const styles = theme => ({
    buttonProgress: {
        color: green[500],
        position: 'absolute',
        top: '50%',
        left: '50%',
        marginTop: -12,
        marginLeft: -12,
    },
});

export default withStyles(styles, { withTheme: true })(SubmitBtn);