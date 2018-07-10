/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

import React from "react";
import Input from '@material-ui/core/es/Input/Input';
import InputLabel from '@material-ui/core/es/InputLabel/InputLabel';
import FormHelperText from '@material-ui/core/es/FormHelperText/FormHelperText';
import FormControl from '@material-ui/core/es/FormControl/FormControl';
import Checkbox from "@material-ui/core/es/Checkbox/Checkbox";
import FormControlLabel from "@material-ui/core/es/FormControlLabel/FormControlLabel";
import Select from "@material-ui/core/es/Select/Select";
import InputAdornment from "@material-ui/core/es/InputAdornment/InputAdornment";
import IconButton from "@material-ui/core/es/IconButton/IconButton";
import Visibility from "@material-ui/icons/es/Visibility";
import VisibilityOff from "@material-ui/icons/es/VisibilityOff";
import CloudUploadIcon from '@material-ui/icons/es/CloudUpload';
import Button from "@material-ui/core/es/Button/Button";
import {translate} from "react-i18next";
import withStyles from "@material-ui/core/es/styles/withStyles";
import styles from "../../../Theme/form.css";

class InputField extends React.Component
{
    constructor(props) {
        super(props);

        this.state = {
            showPassword: false,
        };
    }

    render() {
        const {
            classes,
            fullWidth,
            type,
            id,
            label,
            options,
        } = this.props;

        let errors = InputField.getErrors(this.props.errors, this.props.name);

        if (type === 'select' && options && options.length) {
            let selectProps = Object.assign({}, this.props);
            selectProps.options = undefined;
            delete selectProps.options;

            return (
                <FormControl error={!!errors.length} fullWidth={fullWidth}>
                    <InputLabel htmlFor={id}>{label}</InputLabel>
                    <Select
                        native
                        {...selectProps}
                    >
                        {options}
                    </Select>
                    {errors.length > 0 && <FormHelperText error={true}>{errors}</FormHelperText>}
                </FormControl>
            );
        }

        let props = Object.assign({}, this.props);
        props.label = undefined;
        props.classes = undefined;
        delete props.label;
        delete props.classes;

        if (type === 'file') {
            return (
                <div>
                    <Input className={classes.hiddenInput} {...props}/>
                    <label htmlFor={props.id}>
                        <Button variant="contained" component="span" className={classes.uploadButton}>
                            {label}<CloudUploadIcon className={classes.rightIcon} />
                        </Button>
                        {errors.length > 0 && <FormHelperText error={true}>{errors}</FormHelperText>}
                    </label>
                </div>
            );
        } else if (type === 'checkbox') {
            return (
                <FormControl error={!!errors.length} fullWidth={fullWidth}>
                    <FormControlLabel
                        control={<Checkbox value={"1"} {...props}/>}
                        label={label}
                    />
                    {errors.length > 0 && <FormHelperText error={true}>{errors}</FormHelperText>}
                </FormControl>
            );
        } else if (!props.endAdornment && type === 'password') {
            props.endAdornment= (
                <InputAdornment position="end">
                    <IconButton
                        aria-label="Toggle password visibility"
                        onClick={this.handleClickShowPassword.bind(this)}
                        onMouseDown={this.handleMouseDownPassword}
                    >
                        {this.state.showPassword ? <VisibilityOff /> : <Visibility />}
                    </IconButton>
                </InputAdornment>
            );

            props.type = this.state.showPassword ? 'text' : 'password';
        } else if (type === 'datetime-local') {
            props.defaultValue = props.defaultValue.substr(0, 16);
        }

        return (
            <FormControl error={!!errors.length} fullWidth={fullWidth}>
                <InputLabel htmlFor={id}>{label}</InputLabel>
                <Input {...props}/>
                {errors.length > 0 && <FormHelperText error={true}>{errors}</FormHelperText>}
            </FormControl>
        );
    }

    static getErrors(errors, name) {
        let retErrors = [];

        if (errors) {
            for (let i in errors) {
                let error = errors[i];

                if (error.key.replace('.', '_') == name) {
                    retErrors.push(error.message);
                }
            }
        }

        return retErrors;
    }

    handleMouseDownPassword(event) {
        event.preventDefault();
    };

    handleClickShowPassword() {
        const {showPassword} = this.state;

        this.setState({ showPassword: !showPassword });
    };
}

export default withStyles(styles, {withTheme: true})
(
    InputField
);